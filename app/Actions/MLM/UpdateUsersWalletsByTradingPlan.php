<?php

namespace App\Actions\MLM;

use App\Models\PricingPlanSettings;
use App\Models\TradingPlan;

class UpdateUsersWalletsByTradingPlan
{
    public function __construct(protected UpdateWalletByInterest $updateWalletByInterest)
    {
    }

    public function execute(): void
    {
        $settings = PricingPlanSettings::query()
            ->whereHas('pricingPlan', fn($query) => $query->enabled())
            ->get()
            ->mapWithKeys(fn(PricingPlanSettings $planSettings) => [$planSettings->pricing_plan_id => $planSettings]);

        $rates = $settings->mapWithKeys(fn(PricingPlanSettings $setting, $id) => [$id => $setting->interest_percentage / 100]);

        $query = TradingPlan::query()
            ->with(['user.wallet']);

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
                $wallet = $tradingPlan->user->wallet;
                $rate = $rates[$tradingPlan->pricing_plan_id];
                $this->updateWalletByInterest->execute($wallet, $rate);
            });
    }
}
