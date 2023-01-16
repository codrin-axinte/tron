<?php

namespace Modules\Morphling\Enums;

use Arr;

trait HasValues
{
    public static function values(): array
    {
        return Arr::pluck(static::cases(), 'value');
    }

    public static function collectValues(): \Illuminate\Support\Collection
    {
        return collect(static::values());
    }
}
