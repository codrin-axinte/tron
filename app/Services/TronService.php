<?php

namespace App\Services;

use App\Enums\ChatHooks;
use App\Enums\PendingActionType;
use App\Events\UserActivated;
use App\Http\Integrations\Tron\Data\TransferTokensData;
use App\Http\Integrations\Tron\Requests\TRC20\GetAccountBalanceRequest;
use App\Http\Integrations\Tron\Requests\TRC20\TransferTokensRequest;
use App\Models\User;
use App\Telegram\Traits\HasMessageTemplates;
use DefStudio\Telegraph\Models\TelegraphBot;
use GuzzleHttp\Exception\GuzzleException;
use Modules\Wallet\Models\Wallet;
use Sammyjo20\Saloon\Exceptions\SaloonException;

class TronService
{
    use HasMessageTemplates;

    /**
     * @throws \ReflectionException
     * @throws GuzzleException
     * @throws SaloonException
     */
    public function syncWallets(): static
    {
        $wallets = Wallet::query()->select(['id', 'address'])->whereNotNull('address')->lazy();

        $request = GetAccountBalanceRequest::make();

        foreach ($wallets as $wallet) {
            $request->addData('address', $wallet->address);
            $response = $request
                ->send();

            $wallet->update(['blockchain_amount' => $response->json()]);
        }

        return $this;
    }


    public function syncAccounts(): static
    {
        User::query()
            ->with(['wallet', 'chat'])
            ->doesntHave('roles')
            ->lazy()
            ->each(
                fn(User $user) => $this->activateAccount($user)
            );

        return $this;
    }

    public function activateAccount(User $user): bool
    {
        $wallet = $user->wallet;

        // 1. Get the minimum amount for activation
        // 2. Check if it's above
        // 3. Activate account.
        $minimumAmount = nova_get_setting('minimum_activation_balance', 20);

        if ($wallet->blockchain_amount >= $minimumAmount) {
            $user->assignRole('trader');
            $wallet->amount = $wallet->blockchain_amount;
            $wallet->save();
            $this->sendTemplate(ChatHooks::Activated, $user->chat);
            event(new UserActivated($user));

            return true;
        }


        return false;
    }

    /**
     * @throws \ReflectionException
     * @throws GuzzleException
     * @throws SaloonException
     */
    public function transfer(TransferTokensData $data): \GuzzleHttp\Promise\PromiseInterface
    {
        return TransferTokensRequest::make($data)->sendAsync();
    }

    public function fetchBalance(string $address): int
    {
        return GetAccountBalanceRequest::make()
            ->setData(compact('address'))
            ->send()
            ->json();
    }
}
