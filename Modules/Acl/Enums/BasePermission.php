<?php

namespace Modules\Acl\Enums;

enum BasePermission: string
{
    case All = 'permissions.*';
    case  ViewAny = 'permissions.viewAny';
    case  View = 'permissions.view';
    case  Create = 'permissions.create';
    case  Update = 'permissions.update';
    case  Delete = 'permissions.delete';
    case  Replicate = 'permissions.replicate';
    case  Restore = 'permissions.restore';
    case  Attach = 'permissions.attach';
    case  Detach = 'permissions.detach';
}
