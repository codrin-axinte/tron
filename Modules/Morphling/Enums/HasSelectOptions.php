<?php

namespace Modules\Morphling\Enums;

trait HasSelectOptions
{
    public static function labelValueOptions(): array
    {
        return collect(static::cases())
            ->mapWithKeys(fn ($enum) => [$enum->name => $enum->value])
            ->toArray();
    }

    public static function options($nova = false): array
    {
        $cases = collect(static::cases());

        if ($nova) {
            return $cases->mapWithKeys(fn ($enum) => [$enum->value => $enum->name])
                ->toArray();
        }

        return $cases
            ->map(fn ($enum) => ['label' => $enum->name, 'value' => $enum->value])
            ->toArray();
    }
}
