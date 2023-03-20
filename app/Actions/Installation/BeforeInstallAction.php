<?php

namespace App\Actions\Installation;

use Modules\Acl\Services\AclService;
use Modules\Acl\Utils\AclBuilder;
use Modules\Acl\Utils\AclSeederHelper;

class BeforeInstallAction implements InstallPipeContract
{
    use AclSeederHelper;

    public function handle($payload, \Closure $next)
    {
        $this->acl()
            ->attach(['trade'])
            ->create(AclService::trader());

        return $next($payload);
    }
}
