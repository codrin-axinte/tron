<?php

namespace App\Http\Integrations\Tron\ValueObjects;

use Laravel\Nova\Makeable;

class TRXBalance
{
    use Makeable;

    public function __construct(private string $value)
    {
    }

    public function toTRX(): float
    {
        return $this->value * 0.097195680123302;
    }

    public function gte(int $amountInSUN): bool
    {
        return $this->value >= $amountInSUN;
    }

    public function gt(int $amountInSUN): bool
    {
        return $this->value > $amountInSUN;
    }
}
