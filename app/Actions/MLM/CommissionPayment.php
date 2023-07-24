<?php

namespace App\Actions\MLM;

use App\Events\TelegramHook;
use App\Models\User;
use App\ValueObjects\Percentage;
use App\ValueObjects\USDT;

class CommissionPayment
{

    protected array $commissions = [];
    protected int $maxDepth = 3;
    protected float $amount;


    /**
     * @param array<int, int> $commissions
     * @return CommissionPayment
     */
    public function withCommissions(array $commissions): static
    {
        $this->commissions = $commissions;
        $this->maxDepth = count($commissions);

        return $this;
    }

    public function forAmount(float $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function execute(User $user, $depth = 0): void
    {
        if ($depth >= $this->maxDepth) {
            return;
        }

        $owner = $this->findOwner($user);

        if (!$owner) {
            return;
        }

        $this->payOwner($owner, $depth);

        $this->execute($owner, $depth + 1);
    }

    private function findOwner(?User $user)
    {
        if (!$user) {
            return null;
        }

        $memberOfTeam = $user->memberOfTeams()
            ->with(['owner', 'owner.wallet'])
            ->first();

        return $memberOfTeam?->owner;
    }

    private function payOwner(?User $owner, int $depth = 0): bool
    {
        if (!$owner) {
            return false;
        }

        $commission = $this->commissions[$depth] ?? false;

        if (empty($commission)) {
            return false;
        }

        $amountToGive = USDT::make($this->amount)->percentage($commission);

        $result = $owner->wallet()->increment('amount', $amountToGive->value());
        if ($result) {
            $owner->chat?->markdown(__("You have received :amount from commission.", ['amount' => $amountToGive->value()]))
                ->send();
        }

        return $result;
    }
}
