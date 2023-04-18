<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Menu\Menu;
use Laravel\Nova\Menu\MenuItem;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;
use Modules\Acl\Enums\GenericPermission;
use Modules\Morphling\Events\BootUserMenu;
use Modules\Morphling\Nova\MorphTool;
use Nwidart\Modules\Facades\Module;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Nova::footer(fn ($request) => view('nova-footer', [
            'morph' => $module = Module::findOrFail('morphling'),
            'version' => $module->get('version', '0.0.0'),
        ])->render());

        Nova::initialPath('/dashboards/main');
        Nova::withBreadcrumbs();

        Nova::userMenu(function (Request $request, Menu $menu) {
            $menu->prepend(
                MenuItem::make(
                    __('Account'),
                    "/resources/users/{$request->user()->getKey()}"
                )
            );

            event(new BootUserMenu($menu, $request));

            return $menu;
        });
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
            ->withAuthenticationRoutes()
            ->withPasswordResetRoutes()
            ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return $user->can(GenericPermission::ViewAdmin->value);
        });
    }

    /**
     * Get the dashboards that should be listed in the Nova sidebar.
     *
     * @return array
     */
    protected function dashboards()
    {
        return MorphTool::getNovaDashboards([
            new \App\Nova\Dashboards\Main,
        ]);
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return MorphTool::getNovaTools([
            //
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
