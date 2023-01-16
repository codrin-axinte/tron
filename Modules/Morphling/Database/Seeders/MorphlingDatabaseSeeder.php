<?php

namespace Modules\Morphling\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Acl\Utils\AclSeederHelper;
use Modules\Morphling\Enums\ModulePermission;
use Modules\Morphling\Services\Morphling;

class MorphlingDatabaseSeeder extends Seeder
{
    use AclSeederHelper;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        //app(Morphling::class)->syncModules();

        $this->acl('morph')
            ->onlyWebGuard()
            ->attachEnum(ModulePermission::class)
            ->exceptAdmin()
            ->create();
    }
}
