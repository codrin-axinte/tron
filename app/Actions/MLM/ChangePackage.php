<?php

namespace App\Actions\MLM;

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
            $user->pricingPlans()->attach($plan);
        });
    }
}
