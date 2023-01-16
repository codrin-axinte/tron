<?php

namespace Modules\Settings\Enums;

enum SettingsPermission: string
{
    case All = 'settings.*';
    case ViewAny = 'settings.viewAny';
    case Update = 'settings.update';
    case UpdateSystem = 'settings.system';
}
