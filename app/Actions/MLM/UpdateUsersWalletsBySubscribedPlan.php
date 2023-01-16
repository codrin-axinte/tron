<?php

namespace App\Actions\MLM;

use App\Models\User;

class UpdateUsersWalletsBySubscribedPlan
{
    public function __construct(protected UpdateWalletByInterest $updateWalletByInterest)
    {
    }

    public function execute()
    {
        User::query()
            ->with(['wallet', 'pricingPlans', 'pricingPlans.planSettings'])
            ->whereHas('pricingPlans')
            ->role('trader')
            ->cursor()
            ->each(function (User $user) {
                $plan = $user->pricingPlans()->latest()->first();
                $settings = $plan->planSettings;
                $rate = $settings->interest_percentage / 100;
                $this->updateWalletByInterest->execute($user->wallet, $rate);
            });
    }
}
