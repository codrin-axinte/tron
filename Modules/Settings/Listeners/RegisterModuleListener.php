<?php

namespace Modules\Settings\Listeners;

use Modules\Settings\Enums\SettingsPermission;
use Outl1ne\NovaSettings\NovaSettings;

class RegisterModuleListener
{
    public function __invoke($event): array
    {
        return [
            NovaSettings::make()->canSeeWhen(SettingsPermission::ViewAny->value),
        ];
    }
}
