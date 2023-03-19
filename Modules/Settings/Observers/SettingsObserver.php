<?php

namespace Modules\Settings\Observers;

use Modules\Settings\Services\SettingsService;
use Outl1ne\NovaSettings\Models\Settings;

class SettingsObserver
{
    public function saved(Settings $settings)
    {
        if ($settings->isDirty()) {
            app(SettingsService::class)->syncWithEnv($settings->key, $settings->value);
        }
    }
}
