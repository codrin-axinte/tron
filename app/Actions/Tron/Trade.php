<?php

namespace App\Actions\Tron;

use App\Models\User;
use Modules\Wallet\Models\PricingPlan;
use Throwable;

class Trade
{
    /**
     * @throws Throwable
     */
    public function run(User $user, PricingPlan $plan)
    {
        return \DB::transaction(function () use ($plan, $user) {

            $user->tradingPlans()->create([
                'pricing_plan_id' => $plan->id,
                'amount' => $user->wallet->amount,
            ]);

            $user->wallet()->decrement('amount', $user->wallet->amount);
        });
    }
}
