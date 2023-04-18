<?php

namespace App\Nova;

use App\Nova\Traits\ResourceIsReadonly;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\KeyValue;
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
        return 'Active Trading';
    }

    public static $group = 'tron network';

    public static $polling = true;

    public static $pollingInterval = 15;

    public static $showPollingToggle = true;

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
                ->sortable()
                ->required(),

            BelongsTo::make(__('Pricing Plan'), 'pricingPlan', PricingPlan::class)
                ->searchable()
                ->sortable()
                ->filterable()
                ->required(),

            Currency::make(__('Amount'), 'amount')
                ->symbol('USDT')
                ->filterable()
                ->sortable()
                ->required(),


        ];
    }
}