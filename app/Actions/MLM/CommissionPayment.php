<?php

namespace App\Actions\MLM;

use App\Models\User;
use App\ValueObjects\Percentage;

class CommissionPayment
{
    public function execute(User $user, int $maxDepth = 3, $depth = 0): void
    {
        //FIXME: needs a bit of working.

        if ($depth >= $maxDepth) return;

        $owner = $this->findOwner($user);

        if (!$owner) return;

        $this->payOwner($owner, $depth);

        $this->execute($owner, $maxDepth, $depth++);
    }

    private function findOwner(?User $user)
    {
        if (!$user) return null;

        $memberOfTeam = $user->memberOfTeams()
            ->with(['owner', 'owner.wallet'])
            ->first();

        return $memberOfTeam?->owner;
    }

    private function payOwner(?User $owner, int $depth = 0): bool
    {
        if (!$owner) return false;

        //FIXME: Should determine the amount based on the current plan
        $plan = $owner->pricingPlans()->with('planSettings')->first();

        $settings = $plan->planSettings;
        // $strategy = $settings->commission_strategy;
        $commission = $settings->commissionsByDepth->get($depth);

        if (empty($commission)) {
            return false;
        }

        $amountToGive = Percentage::make($commission)->of($plan->price);

        return $owner->wallet->increment('amount', number_format($amountToGive, 2));
    }
}
