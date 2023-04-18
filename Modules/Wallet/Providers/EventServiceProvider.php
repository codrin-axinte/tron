<?php

namespace Modules\Wallet\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Morphling\Events\BootModulesNovaTools;
use Modules\Wallet\Listeners\RegisterWalletTools;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [

        BootModulesNovaTools::class => [
            RegisterWalletTools::class,
        ],
    ];
}
