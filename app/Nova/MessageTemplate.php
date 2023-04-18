<?php

namespace App\Nova;

use App\Enums\ChatHooks;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\MultiSelect;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class MessageTemplate extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\MessageTemplate>
     */
    public static string $model = \App\Models\MessageTemplate::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name', 'content', 'hooks',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            //ID::make()->sortable(),
            Text::make(__('Name'), 'name')->sortable(),
            Textarea::make(__('Help'), 'help')
                ->nullable()
                ->help('This is only used in the admin panel to remind yourself what and where this template is used.'),
            Markdown::make(__('Content'), 'content')->nullable()->alwaysShow(),
            MultiSelect::make('Hooks')
                ->options(ChatHooks::options(true))
                ->displayUsingLabels()
                ->filterable()
                ->sortable()
                ->nullable(),
        ];
    }
}
