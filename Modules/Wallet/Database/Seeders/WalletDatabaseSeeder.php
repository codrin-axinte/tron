<?php

namespace Modules\Wallet\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Acl\Utils\AclSeederHelper;
use Modules\Wallet\Enums\PricingPlanPermission;
use Modules\Wallet\Enums\WalletPermission;
use Modules\Wallet\Models\PricingPlan;

class WalletDatabaseSeeder extends Seeder
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

        $this->acl('wallet')
            ->attachEnum(WalletPermission::class, WalletPermission::All->value)
            ->attachEnum(PricingPlanPermission::class, PricingPlanPermission::All->value)
            ->create();

        if (! app()->environment('local')) {
            return;
        }

        PricingPlan::factory(2)->create();
        PricingPlan::factory()->best()->create();
    }
}
