<?php

namespace App\Notifications;

use App\Channels\SendsTelegramMessages;
use App\Channels\TelegramChannel;
use App\Contracts\InteractsWithTelegram;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class TokenTransferFailedNotification extends Notification implements ShouldQueue, SendsTelegramMessages
{
    use Queueable;

    public function __construct()
    {
    }

    public function via(mixed $notifiable): array
    {
        return [TelegramChannel::class];
    }

    public function toArray($notifiable): array
    {
        return [];
    }

    public function toTelegram(InteractsWithTelegram $notifiable): string
    {
        return 'Token transfer failed';
    }
}
