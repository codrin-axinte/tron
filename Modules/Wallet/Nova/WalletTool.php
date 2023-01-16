<?php

namespace Modules\Wallet\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Tool;
use Modules\Wallet\Nova\Resources\PricingPlan;
use Modules\Wallet\Nova\Resources\Wallet;

class WalletTool extends Tool
{
    public function boot()
    {
        \Nova::resources([
            Wallet::class,
            PricingPlan::class,
        ]);
    }

    public function menu(Request $request)
    {
        $menu = MenuSection::resource(PricingPlan::class)
            ->icon('credit-card')
            ->canSee(fn () => true);

        //$menu->name = __('Credits Plans');

        return $menu;
    }
}
