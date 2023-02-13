<?php

namespace App\Listeners;

use App\Nova\Settings\ContactsSettingsPage;
use App\Nova\Settings\GeneralSettingsPage;
use App\Nova\Settings\MLMSettingsPage;
use App\Nova\Settings\SMSOSettingsPage;

class RegisterAppSettings
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return array
     */
    public function handle($event): array
    {
        return [
            new GeneralSettingsPage(),
            new MLMSettingsPage(),
        ];
    }
}
