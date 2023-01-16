<?php

namespace Modules\Acl\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Acl\Listeners\RegisterAclNovaTool;
use Modules\Morphling\Events\BootModulesNovaTools;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        BootModulesNovaTools::class => [
            RegisterAclNovaTool::class,
        ],
    ];
}
