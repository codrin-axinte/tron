<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Acl\Enums\UserPermission;
use Modules\Acl\Services\AclService;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user): \Illuminate\Auth\Access\Response|bool
    {
        return $user->can(UserPermission::ViewAny->value);
    }

    public function trade(User $user): bool
    {
        return $user->hasRole(AclService::trader());
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, User $model): \Illuminate\Auth\Access\Response|bool
    {
        if ($this->isMe($user, $model)) {
            return true;
        }

        if ($model->hasRole(AclService::superAdminRole())) {
            return false;
        }

        return $user->can(UserPermission::View->value);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user): \Illuminate\Auth\Access\Response|bool
    {
        return $user->can(UserPermission::Create->value);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, User $model): \Illuminate\Auth\Access\Response|bool
    {
        if ($this->isMe($user, $model)) {
            return true;
        }

        if ($model->hasRole(AclService::superAdminRole())) {
            return false;
        }

        return $user->can(UserPermission::Update->value);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, User $model): \Illuminate\Auth\Access\Response|bool
    {
        if ($this->isMe($user, $model)) {
            return true;
        }

        if ($model->hasRole(AclService::superAdminRole())) {
            return false;
        }

        return $user->can(UserPermission::Delete->value);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, User $model): \Illuminate\Auth\Access\Response|bool
    {
        if ($this->isMe($user, $model)) {
            return true;
        }

        return $user->can(UserPermission::Restore->value);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, User $model): \Illuminate\Auth\Access\Response|bool
    {
        if ($model->hasRole(AclService::superAdminRole())) {
            return false;
        }

        return $user->can(UserPermission::Delete->value);
    }

    private function isMe(User $user, User $model): bool
    {
        return $user->id === $model->id;
    }
}
