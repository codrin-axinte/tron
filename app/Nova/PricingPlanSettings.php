<?php

namespace App\Nova;

use App\Nova\Actions\SimulateInterestRate;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;
use Modules\Wallet\Nova\Resources\PricingPlan;
use Outl1ne\NovaSimpleRepeatable\SimpleRepeatable;

class PricingPlanSettings extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\PricingPlanSettings>
     */
    public static string $model = \App\Models\PricingPlanSettings::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    public static $displayInNavigation = false;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            BelongsTo::make(__('Pricing Plan'), 'pricingPlan', PricingPlan::class)
                ->readonly()
                ->sortable()
                ->filterable(),

            Select::make(__('Commission Strategy'), 'commission_strategy')
                ->options([
                    'default' => __('Direct Amount'),
                 //   'package_percentage' => __('Package Interest Percentage'),
                ])->displayUsingLabels(),

            // Number::make(__('Plan Interest'), 'plan_interest')->min(0),
            SimpleRepeatable::make(__('Commissions/Depth'), 'commissions', [
                Number::make(__('Percentage/Depth'), 'percentage')->min(0)->step(0.01),
            ]),

            Number::make(__('Interest Percentage'), 'interest_percentage')->min(0)->step(0.01),
            Select::make(__('Interest Frequency'), 'interest_frequency')
                ->options([
                    'daily' => __('Daily'),
                    'weekly' => __('Weekly'),
                    'monthly' => __('Monthly'),
                ])->displayUsingLabels(),

            KeyValue::make(__('Meta'), 'meta')->nullable(),
        ];
    }

    public function actions(NovaRequest $request): array
    {
        return [
            SimulateInterestRate::make()
                ->exceptOnIndex()
                ->showInline()
                ->showOnDetail(),
        ];
    }
}
