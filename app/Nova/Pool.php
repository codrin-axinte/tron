<?php

namespace App\Nova;

use App\Nova\Actions\CreateRandomPoolAction;
use Illuminate\Http\Request;
use Laravel\Nova\Actions\ExportAsCsv;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use NormanHuth\SecretField\SecretField;

class Pool extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Pool>
     */
    public static $model = \App\Models\Pool::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'address';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'address'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            Text::make(__('Address'), 'address')->nullable(),

            Currency::make(__('Balance'), 'balance')
                ->symbol('USDT')
                ->filterable()
                ->sortable()
                ->required(),

            Boolean::make(__('Is central'), 'is_central')
                ->help('If this is enabled. All the pools will be merged into this one.')
                ->sortable()
                ->filterable(),

            DateTime::make(__('Created At'), 'created_at')->onlyOnDetail(),
            DateTime::make(__('Updated At'), 'updated_at')->onlyOnDetail(),

            Panel::make(__('Security'), [
                SecretField::make(__('Private Key'), 'private_key')->hideFromIndex()->nullable(),
                KeyValue::make(__('Mnemonic'), 'mnemonic')->nullable(),
            ]),
        ];
    }

    public function actions(NovaRequest $request)
    {
        return [
            CreateRandomPoolAction::make(),
            ExportAsCsv::make()->nameable(),
        ];
    }
}
