<?php

namespace App\Nova;

use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Http\Requests\NovaRequest;

class Team extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Team>
     */
    public static $model = \App\Models\Team::class;

    public static $displayInNavigation = false;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'user.email';

    public static $with = ['user'];

    public function subtitle(): string
    {
        return 'Team';
    }

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
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            BelongsTo::make('Owner', 'owner', User::class)
                ->sortable()
                ->filterable()
                ->searchable()
                ->readonly(),

            BelongsToMany::make('Members', 'members', User::class),
        ];
    }
}
