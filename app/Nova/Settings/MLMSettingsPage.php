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

class MLMSettingsPage extends Page
{
    public function fields(): array
    {
        return [
            Panel::make(__('Withdraw'), [
                Select::make(__('Withdraw method'), 'withdraw_method')->options([
                    'approval' => __('Approval'),
                    'semi' => __('Semi Automatic'),
                    'automatic' => __('Automatic')
                ])->displayUsingLabels(),

                Number::make(__('Withdraw approval amount'), 'withdraw_approval_amount')
                    ->nullable()
                    ->help('If the withdraw request is above this number then it will need manual approval, otherwise will be approved automatically.'),

                Number::make(__('Withdraw maximum amount allowed'), 'withdraw_maximum_amount_allowed')
                    ->help('If left empty then there will be no withdraw limit.')
                    ->nullable(),
            ]),
        ];
    }

    public function defaultValues(): array
    {
        return [
            'withdraw_method' => 'approval',
        ];
    }
}
