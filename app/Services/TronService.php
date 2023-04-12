<?php

namespace App\Services;

use App\Actions\Package\ChangePackage;
use App\Actions\Tron\ApproveTransaction;
use App\Actions\Tron\CreateTransaction;
use App\Actions\Tron\FetchBalance;
use App\Actions\Tron\RejectTransaction;
use App\Actions\Tron\SyncWallet;
use App\Actions\Tron\Withdraw;
use App\Events\UserActivated;
use App\Http\Integrations\Tron\Data\Responses\GenerateWalletResponseData;
use App\Http\Integrations\Tron\Data\TransactionData;
use App\Http\Integrations\Tron\Data\TransferTokensData;
use App\Http\Integrations\Tron\Requests\GenerateRandomWalletRequest;
use App\Http\Integrations\Tron\Requests\TRC20\TransferTokensRequest;
use App\Models\TronTransaction;
use App\Models\User;
use App\Telegram\Traits\HasMessageTemplates;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Collection;
use Modules\Acl\Services\AclService;
use Modules\Wallet\Models\PricingPlan;
use Modules\Wallet\Models\Wallet;
use Sammyjo20\Saloon\Exceptions\SaloonException;

class TronService
{
    use HasMessageTemplates;

    /**
     *   * @return GenerateWalletResponseData
     * @throws GuzzleException
     * @throws SaloonException
     * @throws \ReflectionException
     * @deprecated Use the action class
     */
    public function generateWallet(): GenerateWalletResponseData
    {
        return GenerateWalletResponseData::from(GenerateRandomWalletRequest::make()->send()->json());
    }


    /**
     * Syncs the blockchain amount and the virtual one
     * If the account is not activated with a trader role
     * then it will be activated once it has the minimum amount
     * @throws \ReflectionException
     * @throws GuzzleException
     * @throws SaloonException
     */
    public function syncWallets(): static
    {
        $wallets = Wallet::query()
            ->with('user')
            ->whereNotNull('address')
            ->lazy();

        $syncWalletAction = app(SyncWallet::class);

        foreach ($wallets as $wallet) {
            $syncWalletAction->sync($wallet);
        }

        return $this;
    }


    public function syncAccounts(): static
    {

        $plans = PricingPlan::query()->enabled()->get();

        User::query()
            ->with(['wallet', 'chat', 'pricingPlans'])
            ->lazy()
            ->each(
                fn(User $user) => $this->syncAccount($user, $plans)
            );

        return $this;
    }

    /**
     * @deprecated We're using active trading instead of passive.
     * Now users has to actively select which package they want to trade with
     *
     * @param User $user
     * @param Collection<PricingPlan> $plans
     * @return bool
     * @throws \Throwable
     */
    public function syncAccount(User $user, Collection $plans): bool
    {

        // We should skip if the user is not a trader
        if (!$user->hasAnyRole([AclService::trader()])) {
            return true;
        }

        $defaultRole = AclService::trader();

        $wallet = $user->wallet;
        $balance = $wallet->amount;

        $plansReversed = $plans->sortByDesc('price');
        $currentPlan = $user->pricingPlans->first();
        $changePlan = app(ChangePackage::class);

        /**
         * @var PricingPlan $plan
         */
        foreach ($plansReversed as $key => $plan) {
            $amount = $plan->price;

            if ($balance >= $amount) {
                if ($currentPlan?->id !== $plan->id) {
                    // $user->syncRoles($role);
                    $changePlan->handle($user, $plan, force: true);
                }

                if (!$user->hasRole($defaultRole)) {
                    $user->assignRole($defaultRole);
                    $wallet->amount = $wallet->blockchain_amount;
                    $wallet->save();
                    event(new UserActivated($user));
                }

                break;
            } elseif ($key === $plansReversed->count() - 1) {
                // If we've reached the lowest plan and user still doesn't have enough,
                // then we assign the lowest role and remove any plans
                //$user->syncRoles($role);
                //$changePlan->handle($user, $plan, force: true);
                if ($user->pricingPlans->count() > 0) {
                    $user->pricingPlans()->detach();
                }

                break;
            }
        }


        return true;
    }


    /**
     *
     * @throws \ReflectionException
     * @throws GuzzleException
     * @throws SaloonException
     * @deprecated  Use Transfer action
     */
    public function transfer(TransferTokensData $data)
    {
        $response = TransferTokensRequest::make($data)->send();
        $transactionData = new TransactionData($data, referenceId: $response->json());
        return $this->transaction($transactionData);
    }

    /**
     * @param TransactionData $transactionData
     * @return mixed
     * @deprecated  Use create transaction action
     */
    public function transaction(TransactionData $transactionData)
    {
        return app(CreateTransaction::class)($transactionData);
    }

    /**
     * @throws \ReflectionException
     * @throws GuzzleException
     * @throws SaloonException
     * @deprecated Use withdraw action
     */
    public function withdraw(Wallet $wallet, $amount)
    {
        return app(Withdraw::class)($wallet, $amount);
    }

    /**
     * Approves a transaction
     * @throws \ReflectionException
     * @throws GuzzleException
     * @throws SaloonException
     * @deprecated Use the action class
     */
    public function approve(TronTransaction $transaction): TronTransaction
    {
        return app(ApproveTransaction::class)($transaction);
    }

    /**
     * Rejects a transaction
     *  * @param TronTransaction $transaction
     * @return TronTransaction
     * @deprecated Use the action class
     */
    public function reject(TronTransaction $transaction): TronTransaction
    {
        return app(RejectTransaction::class)($transaction);
    }

    /**
     *   * @throws \ReflectionException
     * @throws GuzzleException
     * @throws SaloonException
     * @deprecated Use the action class
     */
    public function fetchBalance(string $address): int
    {
        return app(FetchBalance::class)($address);
    }
}
