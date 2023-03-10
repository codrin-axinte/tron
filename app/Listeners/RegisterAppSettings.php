<?php

namespace App\Listeners;

use App\Nova\Settings\GeneralSettingsPage;
use App\Nova\Settings\TronSettingsPage;

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
            new TronSettingsPage()
        ];
    }
}
