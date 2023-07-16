<?php

namespace Tests;

use App\Console\Commands\AppInstallCommand;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Modules\Acl\Services\AclService;
use Modules\Acl\Utils\AclSeederHelper;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, AclSeederHelper;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('module:seed', [
            '--no-interaction' => true,
        ]);

        $this->acl()
            ->attach(['trade'])
            ->create(AclService::trader());

    }
}
