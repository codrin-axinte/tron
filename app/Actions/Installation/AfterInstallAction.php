<?php

namespace App\Actions\Installation;

use Modules\Acl\Utils\AclSeederHelper;

class AfterInstallAction implements InstallPipeContract
{
    use AclSeederHelper;

    public function handle($payload, \Closure $next)
    {
        //
        return $next($payload);
    }
}
