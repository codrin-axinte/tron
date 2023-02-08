<?php

namespace App\Actions\MLM;

use App\Services\CompoundInterestCalculator;
use Modules\Wallet\Models\Wallet;

class UpdateWalletByInterest
{
    public function __construct(protected CompoundInterestCalculator $calculator)
    {
    }

    public function execute(Wallet $wallet, float $rate): bool
    {
        // Active: Commission on invitation/referral
        // Passive: Compound

        $principal = $wallet->amount;

        $interest = $this->calculator->compoundInterest($principal, $rate) - $principal;

        return $wallet->update(['amount' => $principal + $interest]);
    }


}
