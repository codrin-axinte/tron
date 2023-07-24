<?php

namespace App\Services;

class CompoundInterestCalculator
{
    public function compoundInterest($principal, $percentageRate): float|int
    {
        $result = $principal + (($percentageRate / 100) * $principal);
        return $result;
    }

    public function calculateInterest($principal, $percentageRate): float|int
    {
        return ($percentageRate / 100) * $principal;
    }

    public function simulate($principal, $rate, $hours = 8760): array
    {
        $data = [];

        for ($i = 1; $i <= $hours; $i++) {
            $interest = $principal * $rate;
            $principal = $principal + $interest;
            $data[] = [
                'hour' => $i,
                'interest' => number_format($interest, 2),
                'principal' => number_format($principal, 2),
            ];
        }

        return $data;
    }
}
