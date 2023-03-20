<?php

namespace Modules\Acl\Services;

use Illuminate\Support\Traits\Macroable;

class AclService
{
    use Macroable;

    public static function defaultRoles()
    {
        return array_filter(config('acl.roles', []));
    }

    public static function superAdminRole(): string
    {
        return config('acl.roles.super_admin', 'super-admin');
    }

    public static function adminRole(): string
    {
        return config('acl.roles.admin', 'admin');
    }

    public static function userRole(): string
    {
        return config('acl.roles.user', 'user');
    }

    public static function frontendApplication()
    {
        return config('acl.roles.frontend', 'frontend_app');
    }

    public static function mobileApplication()
    {
        return config('acl.roles.mobile', 'mobile_app');
    }
}
