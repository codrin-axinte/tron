<?php

namespace App\Nova\Dashboards;

use App\Nova\Metrics\TotalBalance;
use Laravel\Nova\Dashboards\Main as Dashboard;
use Modules\Acl\Enums\UserPermission;
use Modules\Morphling\Nova\Metrics\UsersPerDay;

class Main extends Dashboard
{
    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        return [
            TotalBalance::make(),
            UsersPerDay::make()->canSeeWhen(UserPermission::ViewAny->value),
        ];
    }
}
