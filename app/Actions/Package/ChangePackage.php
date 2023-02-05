<?php

namespace App\Actions\Package;

use App\Enums\PendingActionType;
use App\Models\PendingAction;
use App\Models\User;
use Modules\Wallet\Models\PricingPlan;

class ChangePackage
{
    /**
     * @throws \Throwable
     */
    public function handle(User $user, PricingPlan $plan)
    {
        return \DB::transaction(function () use ($user, $plan) {
            //$user->pricingPlans()->sync($plan);
            PendingAction::query()->create([
                'user_id' => $user->id,
                'type' => PendingActionType::AwaitingTransactionConfirmation,
                'meta' => [
                    'price' => $plan->price, // We store the price in case its modified in the meantime
                    'plan' => $plan->id,
                ],
            ]);
        });
    }
}
