<?php

namespace Modules\Morphling\Nova;

use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\KeyValue;

class Preset
{
    public static function meta(string $column = 'Meta'): KeyValue
    {
        return KeyValue::make($column)->nullable();
    }

    public static function status(string $statusEnum, string $value, string $column = 'Status'): Badge
    {
        return Badge::make($column)
            ->displayUsing(fn () => $statusEnum::from($value)->value)
            ->map($statusEnum::getNovaBadgeColors())
            ->exceptOnForms();
    }
}
