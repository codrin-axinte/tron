<?php

namespace Modules\Acl\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Acl\Enums\RolePermission;
use Spatie\Permission\Models\Role;

class RolePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        return $user->can(RolePermission::ViewAny->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Role $role): bool
    {
        return $user->can(RolePermission::View->value);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can(RolePermission::Create->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Role $role): bool
    {
        return $user->can(RolePermission::Update->value);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Role $role): bool
    {
        return $user->can(RolePermission::Delete->value);
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Role $role): bool
    {
        return $user->can(RolePermission::Replicate->value);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Role $role): bool
    {
        return $user->can(RolePermission::Restore->value);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Role $role): bool
    {
        return $user->can(RolePermission::Delete->value);
    }
}
