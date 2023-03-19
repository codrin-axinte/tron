<?php

namespace Modules\Acl\Listeners;

use Modules\Acl\Nova\PermissionsTool;
use Modules\Morphling\Events\BootModulesNovaTools;

class RegisterAclNovaTool
{
    public function handle(BootModulesNovaTools $event): array
    {
        return [
            PermissionsTool::make(),
        ];
    }
}
