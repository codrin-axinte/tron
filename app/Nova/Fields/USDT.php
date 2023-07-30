<?php

namespace App\Nova\Fields;

use Laravel\Nova\Fields\Currency;

class USDT extends Currency
{
    public function __construct($name, $attribute = null, $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this
            ->displayUsing(fn($amount) => round($amount))
            ->symbol('USDT')
            ->filterable()
            ->sortable();
    }
}
