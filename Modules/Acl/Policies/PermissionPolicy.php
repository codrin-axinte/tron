<?php

namespace Modules\Acl\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Acl\Enums\BasePermission;
use Spatie\Permission\Models\Permission;

class PermissionPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can(BasePermission::ViewAny->value);
    }

    public function view(User $user, Permission $permission): bool
    {
        return $user->can(BasePermission::View->value);
    }

    public function create(User $user): bool
    {
        return $user->can(BasePermission::Create->value);
    }

    public function update(User $user, Permission $permission): bool
    {
        return $user->can(BasePermission::Update->value);
    }

    public function delete(User $user, Permission $permission): bool
    {
        return $user->can(BasePermission::Replicate->value);
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Permission $role): bool
    {
        return $user->can(BasePermission::Replicate->value);
    }

    public function restore(User $user, Permission $permission): bool
    {
        return $user->can(BasePermission::Replicate->value);
    }

    public function forceDelete(User $user, Permission $permission): bool
    {
        return $user->can(BasePermission::Replicate->value);
    }
}
