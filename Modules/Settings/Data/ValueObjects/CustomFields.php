<?php

namespace Modules\Settings\Data\ValueObjects;

use Whitecube\NovaFlexibleContent\Layouts\Collection;

class CustomFields
{
    public function __construct(private Collection $data)
    {
    }

    public function keyValuePair(): Collection
    {
        return $this->data->mapWithKeys(fn ($layout) => [$layout->label => $layout->value]);
    }

    public function format(): Collection
    {
        return $this->data->map(function ($layout) {
            $mapping = [
                '{VALUE}' => $layout->value,
                '{LABEL}' => $layout->label,
            ];

            return str_replace(array_keys($mapping), array_values($mapping), $format ?? $layout->format);
        });
    }
}
