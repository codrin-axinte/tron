<?php

namespace App\Providers;

use App\Services\CompoundInterestCalculator;
use App\Services\PoolManager;
use App\Services\TronService;
use App\Telegram\DefaultWebhookHandler;
use App\Telegram\GuestWebhookHandler;
use App\Telegram\TelegramWebhookHandler;
use App\Updater\Updater;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
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

        $this->app->singleton(TronService::class);;
        $this->app->singleton(PoolManager::class);;
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
