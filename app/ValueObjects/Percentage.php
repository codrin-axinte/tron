<?php

namespace App\ValueObjects;

use Illuminate\Contracts\Support\Arrayable;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use Laravel\Nova\Makeable;

class Percentage implements Arrayable
{
    use Makeable;

    public function __construct(protected null|float|int $value)
    {
    }

    /**
     * Getter for methods/properties.
     */
    public function __get(string $name)
    {
        if (method_exists($this, $name)) {
            return $this->{$name}();
        }
    }

    public function raw(): float|int
    {
        return $this->value;
    }

    #[Pure]
    public function rounded(): float|int
    {
        if (! $this->exists()) {
            return 0;
        }

        return round($this->value);
    }

    public function exists(): bool
    {
        return ! empty($this->value);
    }

    #[Pure]
    public function floor(): float|int
    {
        if (! $this->exists()) {
            return 0;
        }

        return floor($this->value);
    }

    #[Pure]
    public function of($amount): float|int
    {
        if (! $this->exists()) {
            return $amount;
        }

        return ($this->value / 100) * $amount;
    }

    #[Pure]
    public function discounted($amount): float|int
    {
        if (! $this->exists()) {
            return $amount;
        }

        return $amount - $this->of($amount);
    }

    #[Pure]
    public function __toString(): string
    {
        if ($this->exists()) {
            return $this->value.'%';
        }

        return 'N/A';
    }

    #[ArrayShape(['value' => 'float|int|null', 'formatted' => 'string'])]
    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'formatted' => $this->value.'%',
        ];
    }
}
