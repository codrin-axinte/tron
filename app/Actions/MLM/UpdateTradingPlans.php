<?php

namespace App\Actions\MLM;

use App\Enums\ChatHooks;
use App\Events\TelegramHook;
use App\Models\PricingPlanSettings;
use App\Models\TradingPlan;
use App\Services\CompoundInterestCalculator;
use DateTime;

class UpdateTradingPlans
{
    public function __construct(protected CompoundInterestCalculator $calculator)
    {
    }

    public function execute(): void
    {
        $settings = PricingPlanSettings::query()
            ->whereHas('pricingPlan', fn($query) => $query->enabled())
            ->get()
            ->mapWithKeys(fn(PricingPlanSettings $planSettings) => [$planSettings->pricing_plan_id => $planSettings]);

        $this->updateExpiredPlans($settings);
        $this->updateActivePlans($settings);
    }

    private function updateExpiredPlans($settings): void
    {
        $now = now(config('app.timezone'));
        TradingPlan::query()
            ->with(['user'])
            ->lazy()
            ->each(function (TradingPlan $tradingPlan) use ($settings, $now) {
                $diff = $tradingPlan->created_at->diffInHours($now);
                $expiration_hours = $settings[$tradingPlan->pricing_plan_id]->expiration_hours;

                if ($diff < $expiration_hours) {
                    \Log::debug('Skip plan, not expired', [
                        'now' => $now->toDateTimeString(),
                        'expiration_hours' => $expiration_hours,
                        'diff_in_days' => $diff,
                        'created_at' => $tradingPlan->created_at->toDateTimeString()
                    ]);
                    return;
                }

                $result = $tradingPlan->user->wallet()->increment('amount', $tradingPlan->amount);

                if ($result) {
                    $tradingPlan->delete();
                    event(new TelegramHook($tradingPlan->user, ChatHooks::TradingFinished));
                }
            });
    }

    private function updateActivePlans($settings): void
    {
        $rates = $settings
            ->mapWithKeys(fn(PricingPlanSettings $setting, $id) => [$id => $setting->interest_percentage]);

        $now = now(config('app.timezone'));

        TradingPlan::query()
            ->lazy()
            ->each(callback: function (TradingPlan $tradingPlan) use ($rates, $now) {
                $diffInHours = $tradingPlan->updated_at->diffInHours($now);
                $min_hours = 1;

                if ($diffInHours < $min_hours) {
                    \Log::debug('Skip trading plan', [
                        'diff_in_hours' => $diffInHours,
                        'now' => $now->toDateTimeString(),
                        'last_update' => $tradingPlan->updated_at->toDateTimeString(),
                    ]);
                    return;
                }

                $rate = $rates[$tradingPlan->pricing_plan_id];
                $interest = $this->calculator->calculateInterest($tradingPlan->amount, $rate);

                $tradingPlan->increment('amount', $interest);

                //$tradingPlan->amount = $this->calculator->compoundInterest($tradingPlan->amount, $rate);
                //$tradingPlan->save();
            });
    }
}
