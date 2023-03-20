<?php

namespace App\Nova\Settings;

use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;

use Laravel\Nova\Panel;

use Modules\Settings\Contracts\SyncEnv;
use Modules\Settings\Pages\Page;
use Modules\Wallet\Models\PricingPlan;
use NormanHuth\SecretField\SecretField;
use Outl1ne\MultiselectField\Multiselect;
use Outl1ne\NovaSimpleRepeatable\SimpleRepeatable;
use Spatie\Permission\Models\Role;

class TronSettingsPage extends Page
{
    public function fields(): array
    {
        return [
            Number::make(__('Max pool proxying'), 'max_pool_proxy')
                ->help(__('Through how many pools should proxy a withdraw transaction. This helps to fuzzy the transactions history and makes each transaction harder to be traced. However the many the proxies, the more will cost. Set it to 0 or leave it empty to send directly.')),


            Panel::make(__('Withdraw'), [
                Select::make(__('Withdraw method'), 'withdraw_method')->options([
                    'approval' => __('Approval'),
                    'semi' => __('Semi Automatic'),
                    'automatic' => __('Automatic')
                ])->displayUsingLabels(),

                Number::make(__('Withdraw approval amount'), 'withdraw_approval_amount')
                    ->nullable()
                    ->help('If the withdraw request is above this number then it will need manual approval, otherwise will be approved automatically. This works only with semi-automatic method.'),

                Number::make(__('Withdraw maximum amount allowed'), 'withdraw_maximum_amount_allowed')
                    ->help('If left empty then there will be no withdraw limit.')
                    ->nullable(),

                Number::make(__('Withdraw minimum amount allowed'), 'withdraw_minimum_amount_allowed')
                    ->help('If left empty then there will be no withdraw limit.')
                    ->nullable(),

                Boolean::make(__('Block withdraws'), 'block_withdraws')
                    ->help('When this enabled, no user will be able to issue withdraw transactions.'),
            ]),
        ];
    }

    public function defaultValues(): array
    {
        return [
            'max_pool_proxy' => 0,
            'withdraw_method' => 'approval',
        ];
    }
}
