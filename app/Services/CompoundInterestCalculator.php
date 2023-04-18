<?php

namespace App\Services;

class CompoundInterestCalculator
{
    public function compoundInterest($principal, $rate, $time = 1): float|int
    {
        return $principal * (pow((1 + $rate / 365), 365 * $time));
    }

    public function compoundInterestHourly($principal, $annual_rate, $time_in_years): float|int
    {
        // Convert annual rate to hourly rate
        $hourly_rate = $annual_rate / 365 / 24;
        // Calculate final amount with hourly compounding
        $final_amount = $principal * pow(1 + $hourly_rate, 365 * 24 * $time_in_years);

        return $final_amount;
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

    public function simulateHourly($principal, $rate, $hours = 8760): array
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
