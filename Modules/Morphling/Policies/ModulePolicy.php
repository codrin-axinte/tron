<?php

namespace Modules\Morphling\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Morphling\Enums\ModulePermission;
use Modules\Morphling\Models\Module;

class ModulePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function viewAny(User $user)
    {
        return $user->can(ModulePermission::ViewAny->value);
    }

    public function view(User $user, Module $module)
    {
        return $user->can(ModulePermission::View->value);
    }

    public function create(User $user)
    {
        return false;
    }

    public function update(User $user, Module $module)
    {
        return false;
    }

    public function delete(User $user, Module $module)
    {
        return false;
    }

    public function runAction()
    {
        return true;
    }

    public function runDestructiveAction()
    {
        return true;
    }
}
