<?php

namespace App\Nova;

use App\Nova\Fields\USDT;
use App\Nova\Traits\ResourceIsReadonly;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\NovaRequest;
use Modules\Wallet\Nova\Resources\PricingPlan;

class TradingPlan extends Resource
{
    use ResourceIsReadonly;

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\TradingPlan>
     */
    public static $model = \App\Models\TradingPlan::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    public static function label()
    {
        return __('Active Trading');
    }

    public static $group = 'blockchain';

    public static $polling = true;

    public static $pollingInterval = 15;

    public static $showPollingToggle = true;

    public static $with = ['pricingPlan', 'pricingPlan.planSettings'];

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),
            BelongsTo::make(__('User'), 'user', User::class)
                ->searchable()
                ->sortable(),

            BelongsTo::make(__('Pricing Plan'), 'pricingPlan', PricingPlan::class)
                ->searchable()
                ->sortable()
                ->filterable(),

            USDT::make(__('Start Amount'), 'start_amount'),

            USDT::make(__('Amount'), 'amount'),

            DateTime::make(__("Started at"), 'created_at')
                ->filterable()
                ->sortable(),
            DateTime::make(__("Last Updated at"), 'updated_at')
                ->filterable()
                ->sortable(),
        ];
    }
}
