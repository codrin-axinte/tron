<?php

namespace App\Nova\Fields;

use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Number;

class USDT extends Number
{
    public function __construct($name, $attribute = null, $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this
            ->step(0.000001)
            ->resolveUsing(function ($value) {
                return number_format($value, 6);
            })
            ->displayUsing(function ($value) {
                return number_format($value, 6);
            })
            //->symbol('USDT')
            ->filterable()
            ->sortable();
    }
}
