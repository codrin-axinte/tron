<?php

namespace Modules\Acl\Utils;

trait AclSeederHelper
{
    protected function acl(string|null $module = null): AclBuilder
    {
        return app(AclBuilder::class)
            ->forModule($module);
    }
}
