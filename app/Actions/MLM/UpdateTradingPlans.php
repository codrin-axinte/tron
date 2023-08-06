<?php

namespace App\Actions\MLM;

use App\Enums\ChatHooks;
use App\Events\TelegramHook;
use App\Models\PricingPlanSettings;
use App\Models\TradingPlan;
use App\Services\CompoundInterestCalculator;
use DateTime;
use Illuminate\Support\Carbon;

class UpdateTradingPlans
{
    const MIN_HOURS = 1;

    public function __construct(protected CompoundInterestCalculator $calculator)
    {
    }

    public function execute(): void
    {
        $now = now(config('app.timezone'));

        TradingPlan::query()
            ->with(['user', 'pricingPlan', 'pricingPlan.planSettings'])
            ->lazy()
            ->each(function (TradingPlan $tradingPlan) use ($now) {
                $this->updateActivePlans($tradingPlan, $now);
                $this->updateExpiredPlans($tradingPlan, $now);
            });


    }

    private function updateExpiredPlans(TradingPlan $tradingPlan, $now): void
    {
        $diffInHours = $tradingPlan->created_at->diffInHours($now);
        $expiration_hours = $tradingPlan->pricingPlan->planSettings->expiration_hours;

        if ($diffInHours < $expiration_hours) {
            return;
        }

        $result = $tradingPlan->user->wallet()->increment('amount', $tradingPlan->amount);
        $hook = ChatHooks::TradingFailed;

        if ($result) {
            $tradingPlan->delete();
            $hook = ChatHooks::TradingFinished;
        }

        event(new TelegramHook($tradingPlan->user, $hook));
    }

    private function updateActivePlans(TradingPlan $tradingPlan, $now): void
    {
        $diffInHours = $tradingPlan->updated_at->diffInHours($now);

        if ($diffInHours < self::MIN_HOURS) {
            return;
        }

        $rate = $tradingPlan->pricingPlan->planSettings->interest_percentage;
        $interest = $this->calculator->calculateInterest($tradingPlan->amount, $rate);

        $tradingPlan->increment('amount', $interest);
    }
}
