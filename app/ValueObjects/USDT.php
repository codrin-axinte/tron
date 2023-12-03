<?php

namespace App\ValueObjects;

use Laravel\Nova\Makeable;

class USDT
{
    use Makeable;

    public const SUN_UNIT = 1000000;

    public function __construct(private readonly float $amount)
    {
    }

    public function value(): float|int
    {
        return $this->amount;
    }

    public static function fromSun(float $amount): static
    {
        $value = bcdiv($amount, '1000000', 6); // 6 decimal places precision

        return new static($value);
    }

    public function toSun(): float
    {
        $sun = bcmul($this->amount, self::SUN_UNIT, 0); // No decimal places for multiplication
        return is_float($this->amount) || is_int($this->amount) ? $sun : strval($sun);
    }

    public function formatted(int $decimals = 6, string $decimalSeparator = '.', string $thousandsSeparator = ','): string
    {
        return number_format($this->amount, $decimals, $decimalSeparator, $thousandsSeparator);
    }

    public function gte(float|int $comparedAmount): bool
    {
        return $this->amount >= $comparedAmount;
    }

    public function greaterThan(float|int $comparedAmount): bool
    {
        return $this->amount > $comparedAmount;
    }

    public function lte(float|int $comparedAmount): bool
    {
        return $this->amount <= $comparedAmount;
    }

    public function __toString(): string
    {
        return $this->formatted();
    }

    public function percentage($of): USDT
    {
        return USDT::make(Percentage::make($this->amount)->of($of));
    }
}
