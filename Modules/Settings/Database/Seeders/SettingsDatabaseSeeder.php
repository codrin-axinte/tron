<?php

namespace Modules\Settings\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Acl\Utils\AclSeederHelper;
use Modules\Settings\Enums\SettingsPermission;
use Modules\Settings\Services\SettingsService;

class SettingsDatabaseSeeder extends Seeder
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

        // $this->call("OthersTableSeeder");
        app(SettingsService::class)->seedDefaults();

        $this->acl('settings')
            ->attachEnum(SettingsPermission::class, SettingsPermission::All->value)
            ->create();
    }
}
