<?php

namespace Modules\Morphling\Nova;

use Laravel\Nova\Fields\Text;

class DefaultMediaProperties
{
    public static function make(): array
    {
        return [
            Text::make(__('Alt'), 'alt')->nullable(),
            Text::make(__('Title'), 'title')->nullable(),
        ];
    }
}
