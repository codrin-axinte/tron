<?php

namespace Modules\Morphling\Nova;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Laravel\Nova\LogViewer\LogViewer;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Nova;
use Laravel\Nova\Tool;
use Modules\Acl\Enums\GenericPermission;
use Modules\Morphling\Enums\MenuPermission;
use Modules\Morphling\Enums\ModulePermission;
use Modules\Morphling\Events\BootModulesNovaDashboards;
use Modules\Morphling\Events\BootModulesNovaTools;
use Modules\Morphling\Events\BootNovaMenuPositions;
use Modules\Morphling\Events\BootNovaMenuTypes;
use Modules\Morphling\Nova\Resources\Module;
use Modules\Morphling\Utils\DataAggregator;
use Outl1ne\MenuBuilder\MenuBuilder;

class MorphTool extends Tool
{
    protected static array $resources = [
       // Module::class,
    ];

    public function boot()
    {
        Nova::resources(static::$resources);
    }

    public function menu(Request $request)
    {
        return [];
    /*    return MenuSection::resource(Module::class)
            ->icon('chip')
            ->canSee(fn () => $request->user()->can(ModulePermission::ViewAny->value));*/
    }

    public function getLocales()
    {
        return config('nova-translatable.locales', []);
    }

    public static function clientUrl($path = '/'): string
    {
        return URL::format(config('app.frontend_url'), $path);
    }

    public static function getNovaDashboards(array $dashboards = []): array
    {
        return DataAggregator::event(
            event: new BootModulesNovaDashboards(),
            mergeBefore: $dashboards
        )->toArray();
    }

    /**
     *  Collect all module tools.
     *
     * @param  array  $tools
     * @return array
     */
    public static function getNovaTools(array $tools = []): array
    {
        return DataAggregator::event(
            event: new BootModulesNovaTools(),
            mergeAfter: [
                MorphTool::make(),
                LogViewer::make()->canSeeWhen(GenericPermission::ViewLogs->value),
            ],
            mergeBefore: [
                MenuBuilder::make()->canSeeWhen(MenuPermission::ViewAny->value),
                ...$tools,
            ]
        )->toArray();
    }

    public static function getMenuPositions(): array
    {
        return DataAggregator::event(event: new BootNovaMenuPositions(), mergeBefore: [
            MenuPosition::make('header'),
            MenuPosition::make('footer')->setDepth(2),
            MenuPosition::make('user_dashboard')->setDepth(2),
            MenuPosition::make('user_menu')->setDepth(1),
        ])->mapWithKeys(fn (MenuPosition $position) => [$position->getId() => $position->toArray()])
            ->toArray();
    }

    public static function getMenuTypes(): array
    {
        return DataAggregator::event(event: new BootNovaMenuTypes(), mergeBefore: [
            \Outl1ne\MenuBuilder\MenuItemTypes\MenuItemTextType::class,
            \Outl1ne\MenuBuilder\MenuItemTypes\MenuItemStaticURLType::class,
            \Modules\PageManager\Nova\MenuTypes\MenuItemPage::class,
        ])->toArray();
    }
}
