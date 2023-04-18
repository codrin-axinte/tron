<?php

namespace App\Actions\MLM;

use App\Enums\ChatHooks;
use App\Events\TelegramHook;
use App\Models\PricingPlanSettings;
use App\Models\TradingPlan;
use App\Services\CompoundInterestCalculator;

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


        $this->updateActivePlans($settings);
        $this->updateExpiredPlans($settings);

    }

    private function updateExpiredPlans($settings): void
    {
        $query = TradingPlan::query()
            ->with(['user']);

        $settings
            ->each(function (PricingPlanSettings $settings, $index) use ($query) {
                $hours = now()->subHours($settings->expiration_hours);
                if ($index === 0) {
                    $query->where('created_at', '<', $hours);
                } else {
                    $query->orWhere('created_at', '<', $hours);
                }
            });

        $query
            ->cursor()
            ->each(function (TradingPlan $tradingPlan) {
                $result = $tradingPlan->user->wallet()->increment('amount', $tradingPlan->amount);

                event(new TelegramHook($tradingPlan->user, ChatHooks::TradingFinished));

                if ($result) {
                    $tradingPlan->delete();
                }
            });
    }


    private function updateActivePlans($settings): void
    {
        $rates = $settings->mapWithKeys(fn(PricingPlanSettings $setting, $id) => [$id => $setting->interest_percentage / 100]);

        $query = TradingPlan::query();

        $settings
            ->each(function (PricingPlanSettings $settings, $index) use ($query) {
                $hours = now()->subHours($settings->expiration_hours);

                if ($index === 0) {
                    $query->where('created_at', '>=', $hours);
                } else {
                    $query->orWhere('created_at', '>=', $hours);
                }

            });

        $query
            ->cursor()
            ->each(function (TradingPlan $tradingPlan) use ($rates) {
                $rate = $rates[$tradingPlan->pricing_plan_id];
                $principal = $tradingPlan->amount;
                $amount = $this->calculator->compoundInterest($principal, $rate) - $principal;
                $tradingPlan->increment('amount', $amount);
            });
    }
}