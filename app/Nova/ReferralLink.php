<?php

namespace App\Nova;

use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class ReferralLink extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\ReferralLink>
     */
    public static string $model = \App\Models\ReferralLink::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'code';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'code',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            // ID::make()->sortable(),
            BelongsTo::make(__('User'), 'user', User::class)
                ->sortable()
                ->searchable()
                ->filterable(),
            Text::make(__('Invitation Code'), 'code')->required(),
        ];
    }
}
