<?php

namespace App\Services;

use App\Actions\Package\ChangePackage;
use App\Enums\ChatHooks;
use App\Events\UserActivated;
use App\Http\Integrations\Tron\Data\Responses\GenerateWalletResponseData;
use App\Http\Integrations\Tron\Data\TransferTokensData;
use App\Http\Integrations\Tron\Requests\GenerateRandomWalletRequest;
use App\Http\Integrations\Tron\Requests\TRC20\GetAccountBalanceRequest;
use App\Http\Integrations\Tron\Requests\TRC20\TransferTokensRequest;
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


    public function generateWallet(): GenerateWalletResponseData
    {
        return GenerateWalletResponseData::from(GenerateRandomWalletRequest::make()->send()->json());
    }


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

        $plans = PricingPlan::query()->enabled()->get();

        User::query()
            ->with(['wallet', 'chat', 'pricingPlans'])
            //->doesntHave('roles')
            ->lazy()
            ->each(
                fn(User $user) => $this->upgradeOrDowngradeAccount($user, $plans)
            );

        return $this;
    }

    /**
     * @param User $user
     * @param Collection<PricingPlan> $plans
     * @return bool
     * @throws \Throwable
     */
    public function upgradeOrDowngradeAccount(User $user, Collection $plans): bool
    {

        // We should skip if the user is admin
        if ($user->hasAnyRole([AclService::adminRole(), AclService::superAdminRole()])) {
            return true;
        }

        $defaultRole = AclService::trader();

        $wallet = $user->wallet;
        $balance = $wallet->amount;

        $plansReversed = $plans->sortByDesc('price');
        $currentPlan = $user->subscribedPlan();
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
                    $user->pricingPlans()->delete();
                }

                break;
            }
        }


        return true;
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
