<?php

namespace Modules\Acl\Enums;

enum RolePermission: string
{
    case All = 'roles.*';
    case  ViewAny = 'roles.viewAny';
    case  View = 'roles.view';
    case  Create = 'roles.create';
    case  Update = 'roles.update';
    case  Delete = 'roles.delete';
    case  Replicate = 'roles.replicate';
    case  Restore = 'roles.restore';
    case  Attach = 'roles.attach';
    case  Detach = 'roles.detach';
}
