<?php

namespace App\Nova;

use App\Nova\Traits\ResourceIsReadonly;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Http\Requests\NovaRequest;

class Team extends Resource
{
    use ResourceIsReadonly;

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Team>
     */
    public static $model = \App\Models\Team::class;

    public static $displayInNavigation = true;

    public static $group = 'mlm';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'owner.email';

    public static $with = ['owner'];

    public function subtitle(): string
    {
        return __('Team');
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
     * @param NovaRequest $request
     * @return array
     */
    public function fields(NovaRequest $request): array
    {
        return [
            BelongsTo::make('Owner', 'owner', User::class)
                ->sortable()
                ->filterable()
                ->searchable()
                ->readonly(),

            BelongsToMany::make(
                __('Team'),
                'members',
                User::class
            )->searchable()
                ->filterable(),
        ];
    }
}
