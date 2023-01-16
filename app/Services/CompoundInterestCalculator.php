<?php

namespace App\Services;

class CompoundInterestCalculator
{
    public function compoundInterest($principal, $rate, $time = 1): float|int
    {
        return $principal * (pow((1 + $rate / 365), 365 * $time));
    }

    public function simulate($principal, $rate, $time = 1, $days = 365): array
    {
        $data = [];

        for ($i = 1; $i <= $days; $i++) {
            $interest = $this->compoundInterest($principal, $rate, $time) - $principal;
            $principal = $principal + $interest;
            $data[] = [
                'day' => $i,
                'interest' => number_format($interest, 2),
                'principal' => number_format($principal, 2),
            ];
        }

        return $data;
    }
}
