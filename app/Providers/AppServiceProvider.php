<?php

namespace App\Providers;

use App\Services\CompoundInterestCalculator;
use App\Updater\Updater;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(CompoundInterestCalculator::class);

        $this->app->singleton(Updater::class, function ($app) {
            $config = $app['config']->get('updater');

            return new Updater($config['version'], $config['updates']);
        });

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // JsonResource::withoutWrapping();
        // Carbon::setLocale('ro_RO');
    }

    public function provides()
    {
        return [Updater::class];
    }
}
