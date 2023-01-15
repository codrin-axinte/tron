<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Modules\Acl\Services\AclService;
use Modules\Morphling\Nova\MorphTool;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        ResetPassword::createUrlUsing(
            fn ($user, $token) => MorphTool::clientUrl("/auth/reset-password?email={$user->email}&token={$token}"),
        );

        Gate::before(function ($user, $ability) {
            return $user->hasRole(AclService::superAdminRole()) ? true : null;
        });
    }
}
