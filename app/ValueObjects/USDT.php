<?php

namespace App\ValueObjects;

use Laravel\Nova\Makeable;

class USDT
{
    use Makeable;

    public const SUN_UNIT = 1000000;

    public function __construct(private readonly float|int $amount)
    {
    }

    public function value(): float|int
    {
        return $this->amount;
    }

    public static function makeFromSun(float|int $amount): static
    {
        return new static($amount / self::SUN_UNIT);
    }

    public function toSun(): float|int
    {
        return $this->amount * self::SUN_UNIT;
    }

    public function formatted(int $decimals = 2, string $decimalSeparator = '.', string $thousandsSeparator = ','): string
    {
        return number_format($this->amount, $decimals, $decimalSeparator, $thousandsSeparator);
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
