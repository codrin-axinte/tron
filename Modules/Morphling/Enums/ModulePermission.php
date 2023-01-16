<?php

namespace Modules\Morphling\Enums;

enum ModulePermission: string
{
    case All = 'modules.*';
    case  ViewAny = 'modules.viewAny';
    case  View = 'modules.view';
    case  Install = 'modules.install';
    case  Enable = 'modules.enable';
    case  Disable = 'modules.disable';
    case  Update = 'modules.update';
    case  Delete = 'modules.delete';
}
