<?php

namespace Modules\Settings\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Morphling\Events\BootModulesNovaTools;
use Modules\Settings\Events\BootSettingsPage;
use Modules\Settings\Listeners\RegisterModuleListener;
use Modules\Settings\Listeners\RegisterPagesListener;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        BootModulesNovaTools::class => [
            RegisterModuleListener::class,
        ],

        BootSettingsPage::class => [
            RegisterPagesListener::class,
        ],
    ];
}
