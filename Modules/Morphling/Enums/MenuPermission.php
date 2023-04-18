<?php

namespace Modules\Morphling\Enums;

enum MenuPermission: string
{
    case All = 'menus.*';
    case ViewAny = 'menus.viewAny';
    case View = 'menus.view';
    case Update = 'menus.update';
    case Delete = 'menus.delete';
}
