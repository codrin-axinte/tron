<?php

namespace App\Actions\Tron;

use App\Models\User;
use App\ValueObjects\USDT;
use Modules\Wallet\Models\PricingPlan;
use Throwable;

class StartTrade
{
    /**
     * @throws Throwable
     */
    public function run(User $user, $plan, $tradeAmount)
    {
        return \DB::transaction(function () use ($plan, $tradeAmount, $user) {
            $this->canTrade($user, $plan, $tradeAmount);

            $user->tradingPlans()->create([
                'pricing_plan_id' => $plan->id,
                'start_amount' => $tradeAmount,
                'amount' => $tradeAmount,
            ]);

            $user->wallet()->decrement('amount', $tradeAmount);
        });
    }

    /**
     * @throws \Exception
     */
    private function canTrade($user, $plan, $tradeAmount): void
    {
        if ($tradeAmount > $user->wallet->amount) {
            throw new \Exception(__('trading.insufficient_funds'));
        }

        if (!$plan) {
            throw new \Exception(__('trading.no_plan'));
        }

        if ($user->tradingPlan()->exists()) {
            throw new \Exception(__('trading.in_progress', ['plan' => $plan->name]));
        }
    }
}
