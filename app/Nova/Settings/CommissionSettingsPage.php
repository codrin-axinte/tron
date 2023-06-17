<?php

namespace App\Nova\Settings;

use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Panel;
use Modules\Settings\Contracts\SyncEnv;
use Modules\Settings\Pages\Page;
use NormanHuth\SecretField\SecretField;
use Outl1ne\NovaSimpleRepeatable\SimpleRepeatable;

class CommissionSettingsPage extends Page
{

    protected array $casts = [
        'commissions' => 'array',
    ];

    public function fields(): array
    {
        return [
            Select::make(__('Strategy'), 'commission_strategy')
                ->options([
                    'default' => __('Direct Amount'),
                    //   'package_percentage' => __('Package Interest Percentage'),
                ])->displayUsingLabels(),

            SimpleRepeatable::make(__('Commissions'), 'commissions', [
                Number::make(__('Percentage/Depth'), 'percentage')
                    ->min(0)
                    ->step(0.01),
            ]),
        ];
    }

    public function defaultValues(): array
    {
        return [
            'commission_strategy' => 'default',
        ];
    }
}
