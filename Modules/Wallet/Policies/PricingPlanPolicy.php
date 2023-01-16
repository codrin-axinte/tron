<?php

namespace Modules\Wallet\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Wallet\Enums\PricingPlanPermission;
use Modules\Wallet\Models\PricingPlan;

class PricingPlanPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        return $user->can(PricingPlanPermission::ViewAny->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, PricingPlan $plan)
    {
        if (! $user) {
            return $plan->enabled;
        }

        return $user->can(PricingPlanPermission::View->value);
    }
}
