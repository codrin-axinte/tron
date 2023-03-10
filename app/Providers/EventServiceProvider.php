<?php

namespace App\Providers;

use App\Events\UserActivated;
use App\Listeners\RegisterAppSettings;
use App\Listeners\SendTemplateMessage;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Settings\Events\BootSettingsPage;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        BootSettingsPage::class => [
            RegisterAppSettings::class,
        ],

        UserActivated::class => [
            SendTemplateMessage::class,
        ]

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
