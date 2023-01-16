<?php

namespace Modules\Acl\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\Acl\Services\AclService;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // User::factory()->create()->assignRole(AclService::superAdminRole());
        // User::factory()->create()->assignRole(AclService::adminRole());
        // User::factory()->create()->assignRole(AclService::userRole());
        // User::factory()->create();
    }
}
