<?php

namespace Modules\Acl\Enums;

use Modules\Morphling\Enums\HasValues;

enum GenericPermission: string
{
    use HasValues;

    case CanImpersonate = 'impersonate';
    case ProtectImpersonation = 'impersonate.protect';
    case ViewAdmin = 'viewAdmin';
    case ManageTokens = 'manageTokens';
    case ViewTelescope = 'viewTelescope';
    case ViewHorizon = 'viewHorizon';
    case ViewLogs = 'viewLogs';
}
