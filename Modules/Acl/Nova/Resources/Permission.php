<?php

namespace Modules\Acl\Nova\Resources;

use App\Nova\Resource;
use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\Traits\HasTabs;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Modules\Morphling\Services\Morphling;

class Permission extends Resource
{
    use HasTabs;

    public static string $model = \Spatie\Permission\Models\Permission::class;

    public static $displayInNavigation = false;

    public static $tableStyle = 'tight';

    public static $title = 'name';

    public static $search = [
        'id',
        'name',
    ];

    public function fields(NovaRequest $request)
    {
        $guards = $this->mapGuards();
        $types = $this->mapTypes($guards);

        return [
            // ID::make()->sortable(),
            Text::make('Name')->sortable(),
            Select::make('Module')
                ->options(app(Morphling::class)->modulesKeyValuePair())
                ->displayUsingLabels()
                ->sortable()
                ->filterable(),

            Badge::make(__('Guard'), 'guard_name')
                ->map($types),

            Select::make('Guard Name')
                ->onlyOnForms()
                ->options($guards)
                ->sortable()
                ->displayUsingLabels()
                ->filterable(),

            Tabs::make(__('Relations'), [
                BelongsToMany::make('Roles', 'roles', Role::class),
            ]),
        ];
    }

    public function authorizedTo(Request $request, $ability): bool
    {
        return $request->user()->can('permissions.'.$ability);
    }

    public function title(): string
    {
        $model = $this->model();

        $title = Str::of($model->name)
            ->replace('.', ' ')
            ->replace('*', __('All'))
            ->append(" - {$model->guard_name}");

        if ($model->module) {
            $title->append(" - {$model->module}");
        }

        return $title;
    }

    private function mapGuards(): array
    {
        return collect(config('auth.guards', []))
            ->keys()
            ->mapWithKeys(fn (string $guard) => [$guard => Str::headline($guard)])
            ->toArray();
    }

    private function mapTypes(array $guards): array
    {
        return collect($guards)->keys()->mapWithKeys(function (string $guard) {
            $type = match ($guard) {
                'web' => 'success',
                'sanctum' => 'warning',
                default => 'info',
            };

            return [$guard => $type];
        })->toArray();
    }
}
