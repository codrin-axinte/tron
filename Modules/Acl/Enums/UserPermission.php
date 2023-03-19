<?php

namespace Modules\Acl\Enums;

enum UserPermission: string
{
    case All = 'users.*';
    case  ViewAny = 'users.viewAny';
    case  ViewOwned = 'users.viewOwned';
    case  View = 'users.view';
    case  Create = 'users.create';
    case  Update = 'users.update';
    case  Delete = 'users.delete';
    case  Replicate = 'users.replicate';
    case  Restore = 'users.restore';
    case  Attach = 'users.attach';
    case  Detach = 'users.detach';
}
