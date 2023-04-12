<?php

namespace App\Actions\MLM;

use App\Models\User;

/**
 * @deprecated use trading plan update
 */
class UpdateUsersWalletsBySubscribedPlan
{
    public function __construct(protected UpdateWalletByInterest $updateWalletByInterest)
    {
    }

    public function execute()
    {
        User::query()
            ->withWhereHas('pricingPlans', function($query) {
                $hours = now()->subHours(6);
                return $query->wherePivot('created_at', '>=', $hours);
            })
            ->with(['wallet', 'pricingPlans.planSettings'])
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
