<?php

namespace App\Events;

use App\Contracts\SendsMessageTemplates;
use App\Enums\ChatHooks;
use App\Models\User;
use DefStudio\Telegraph\Models\TelegraphChat;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserJoined implements SendsMessageTemplates
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(public User $user)
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }

    public function hooks(): array|string|ChatHooks
    {
        return ChatHooks::Joined;
    }

    public function chat(): TelegraphChat
    {
        return $this->user->chat;
    }
}
