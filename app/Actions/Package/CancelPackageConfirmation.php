<?php

namespace App\Actions\Package;

use App\Models\User;
use Modules\Wallet\Models\PricingPlan;

class CancelPackageConfirmation
{
    /**
     * @throws \Throwable
     */
    public function __invoke(User $user, ?PricingPlan $plan = null): mixed
    {
        return \DB::transaction(function () use ($user, $plan) {

            if (is_null($plan)) {
                return $user->pendingActions()
                    ->awaitsConfirmation()
                    ->latest()
                    ->delete();
            }


            return $user->pendingActions()
                ->awaitsConfirmation()
                ->where('meta->plan', $plan->id)
                ->delete();
        });
    }
}
