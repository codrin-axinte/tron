<?php

namespace App\Actions\Package;

use App\Enums\PendingActionType;
use App\Models\PendingAction;
use App\Models\User;
use Modules\Wallet\Models\PricingPlan;

/**
 * @deprecated
 */
class ChangePackage
{
    /**
     * @throws \Throwable
     */
    public function handle(User $user, PricingPlan $plan, bool $force = false)
    {
        return \DB::transaction(function () use ($user, $plan, $force) {
            PendingAction::query()->create([
                'user_id' => $user->id,
                'type' => $force ? PendingActionType::Confirmed : PendingActionType::AwaitingTransactionConfirmation,
                'meta' => [
                    'price' => $plan->price, // We store the price in case its modified in the meantime
                    'plan' => $plan->id,
                ],
            ]);

            if ($force) {
                $user->pricingPlans()->sync($plan);
            }
        });
    }
}
