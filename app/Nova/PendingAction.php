<?php

namespace App\Nova;

use App\Nova\Traits\ResourceIsReadonly;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class PendingAction extends Resource
{
    use ResourceIsReadonly;

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\PendingAction>
     */
    public static $model = \App\Models\PendingAction::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';
    public static $group = 'system';
    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'type'
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
            BelongsTo::make(__('User'), 'user', User::class)
                ->searchable()
                ->filterable()
                ->sortable(),

            Text::make(__('Type'), 'type')->sortable(),

            DateTime::make('Created At', 'created_at')
                ->filterable()
                ->sortable(),

            KeyValue::make(__('Meta'), 'meta'),
        ];
    }


    /**
     * Get the actions available for the resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
