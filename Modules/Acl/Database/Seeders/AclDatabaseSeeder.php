<?php

namespace Modules\Acl\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Acl\Enums\BasePermission;
use Modules\Acl\Enums\GenericPermission;
use Modules\Acl\Enums\RolePermission;
use Modules\Acl\Enums\UserPermission;
use Modules\Acl\Services\AclService;
use Modules\Acl\Utils\AclSeederHelper;
use Spatie\Permission\Models\Role;

class AclDatabaseSeeder extends Seeder
{
    use AclSeederHelper;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Model::unguard();

        $roles = AclService::defaultRoles();

        foreach ($roles as $key => $currentRole) {
            Role::firstOrCreate(['name' => $currentRole]);
        }

        $this->acl('generic')
            ->onlyWebGuard()
            ->attachEnum(GenericPermission::class, [
                GenericPermission::ViewAdmin->value,
                GenericPermission::ProtectImpersonation->value,
                GenericPermission::CanImpersonate->value,
            ])
            ->create();

        $this->acl('acl')
            ->withSanctumGuard()
            ->attachEnum(BasePermission::class, BasePermission::All->value)
            ->attachEnum(RolePermission::class, RolePermission::All->value)
            ->attachEnum(UserPermission::class, UserPermission::All->value)
            ->create();

        //$role = Role::firstOrCreate(['name' => AclService::userRole()]);
        //$role->givePermissionTo(GenericPermission::ViewAdmin->value);

        if (app()->environment('local')) {
            $this->call(SampleDataSeeder::class);
        }
    }
}
