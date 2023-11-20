<?php

namespace App\Providers;

use App\Services\CompoundInterestCalculator;
use App\Services\PoolManager;
use App\Telegram\DefaultWebhookHandler;
use App\Updater\Updater;
use Illuminate\Support\ServiceProvider;
use Modules\Acl\Services\AclService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(DefaultWebhookHandler::class, function () {
            return new DefaultWebhookHandler(value(config('tron.telegram_commands', [])));
        });


        $this->app->singleton(PoolManager::class);
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
        AclService::macro('trader', fn() => config('tron.default_role'));
    }

    public function provides()
    {
        return [Updater::class];
    }
}
