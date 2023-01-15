<?php

namespace App\Http\Controllers;

use App\Enums\NotificationGroup;
use App\Http\Resources\NotificationResource;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\ValidationException;
use Validator;

class NotificationsController extends Controller
{
    /**
     * @throws ValidationException
     */
    private function validateGroup(string $group): void
    {
        Validator::make(compact('group'), [
            'group' => ['required', new Enum(NotificationGroup::class)],
        ])->validate();
    }

    /**
     * @throws ValidationException
     */
    public function index(Request $request, string $group): AnonymousResourceCollection
    {
        $this->validateGroup($group);

        $notifications = $request->user()
            ->notifications()
            ->whereJsonContains('data->group', $group)
            ->limit(15)
            ->get();

        return NotificationResource::collection($notifications);
    }

    /**
     * @throws AuthorizationException
     * @throws ValidationException
     */
    public function markAsRead(Request $request, string $group, DatabaseNotification $notification): AnonymousResourceCollection
    {
        $this->authorize('update', $notification);

        $this->validateGroup($group);

        $notification->read_at = now();
        $notification->save();

        $user = $request->user();
        $notifications = $user
            ->notifications()
            ->whereJsonContains('data->group', $group)
            ->limit(15)
            ->get();

        return NotificationResource::collection($notifications);
    }

    /**
     * @throws ValidationException
     */
    public function markAllAsRead(Request $request, string $group): AnonymousResourceCollection
    {
        $this->validateGroup($group);

        $user = $request->user();

        $user
            ->unreadNotifications()
            ->whereJsonContains('data->group', $group)
            ->update(['read_at' => now()]);

        $notifications = $user
            ->notifications()
            ->whereJsonContains('data->group', $group)
            ->limit(15)
            ->get();

        return NotificationResource::collection($notifications);
    }

    /**
     * @throws ValidationException
     */
    public function count(Request $request, string $group)
    {
        $this->validateGroup($group);

        return $request->user()
            ->unreadNotifications()
            ->whereJsonContains('data->group', $group)
            ->count();
    }
}
