<?php

namespace App\FeatherTemplate\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * @mixin \App\FeatherTemplate\Feather
 */
class Feather extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \App\FeatherTemplate\Feather::class;
    }
}
