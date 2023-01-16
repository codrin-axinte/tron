<?php

namespace Modules\Morphling\Nova\Resources;

use Illuminate\Support\Arr;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource;
use Modules\Morphling\Enums\ModulePermission;
use Modules\Morphling\Nova\Actions\DeleteModule;
use Modules\Morphling\Nova\Actions\InstallModule;
use Modules\Morphling\Nova\Actions\SyncModules;
use Modules\Morphling\Nova\Actions\ToggleModule;
use Modules\Morphling\Nova\Actions\UpdateModule;

class Module extends Resource
{
    public static string $model = \Modules\Morphling\Models\Module::class;

    public static $displayInNavigation = false;

    public static $title = 'name';

    public static $search = [
        'name', 'description', 'keywords', 'author', 'version',
    ];

    public static $tableStyle = 'tight';

    public function fields(NovaRequest $request): array
    {
        return [
            Boolean::make(__('Enabled'), 'enabled')
                ->showOnPreview()
                ->sortable()
                ->filterable(),

            Text::make(__('Title'), 'title')
                ->showOnPreview()
                ->sortable(),

            Text::make(__('Author'), 'author')
                ->showOnPreview()
                ->sortable(),

            Text::make(__('Version'), 'version')
                ->showOnPreview()
                ->sortable(),

            Textarea::make(__('Description'), 'description')
                ->showOnPreview()
                ->rows(2),

            Number::make(__('Priority'), 'priority')
                ->sortable()
                ->filterable()
                ->hideFromIndex()
                ->default(fn () => 0),

            $this->arrayField(__('Keywords'), 'keywords'),
            $this->arrayField(__('Requirements'), 'requirements'),
        ];
    }

    private function arrayField($label, $attribute): Textarea
    {
        return Textarea::make($label, $attribute)
            ->resolveUsing(fn () => Arr::join($this->{$attribute}, ', '))
            ->showOnPreview()
            ->rows(2);
    }

    public function actions(NovaRequest $request)
    {
        return [
            ToggleModule::make()->showInline()->canSee(fn () => $request->user()->can([ModulePermission::Enable->value, ModulePermission::Disable->value])),
            UpdateModule::make()->showInline()->canSeeWhen(ModulePermission::Update->value),
            DeleteModule::make()->showInline()->exceptOnIndex()->canSeeWhen(ModulePermission::Delete->value),
            SyncModules::make()->standalone()->canSeeWhen(ModulePermission::ViewAny->value),
            //InstallModule::make()->standalone()->canSeeWhen(ModulePermission::Install->value), // TODO: Not working yet. Modules must be installed manually
        ];
    }
}
