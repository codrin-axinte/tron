<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Notifications\DatabaseNotification as Notification;

class NotificationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param    $notification
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Notification $notification)
    {
        return $this->isOwned($user, $notification);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Notification $notification)
    {
        return $this->isOwned($user, $notification);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user

     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Notification $notification)
    {
        return $this->isOwned($user, $notification);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user

     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Notification $notification)
    {
        return $this->isOwned($user, $notification);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user

     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Notification $notification)
    {
        return $this->isOwned($user, $notification);
    }

    private function isOwned(User $user, Notification $notification)
    {
        return $notification->notifiable_type === $user->getMorphClass() && $notification->notifiable_id === $user->id;
    }
}
