<?php

namespace App\Events;

use App\Contracts\SendsMessageTemplates;
use App\Enums\ChatHooks;
use App\Models\User;
use DefStudio\Telegraph\Models\TelegraphChat;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TelegramHook implements SendsMessageTemplates
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param  ChatHooks|array<ChatHooks>  $hooks
     * @return void
     */
    public function __construct(public User $user, public ChatHooks|string|array $hooks, public array $payload = [])
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
        return $this->hooks;
    }

    public function chat(): TelegraphChat
    {
        return $this->user->chat;
    }
}
