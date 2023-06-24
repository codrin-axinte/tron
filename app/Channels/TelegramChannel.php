<?php

namespace App\Channels;

use App\Contracts\InteractsWithTelegram;
use App\Telegram\Traits\HasChatMenus;


class TelegramChannel
{
    /**
     * Send the given notification.
     */
    public function send(InteractsWithTelegram $notifiable, SendsTelegramMessages $notification): void
    {
        $message = $notification->toTelegram($notifiable);

        $notifiable
            ->chat
            ->markdown($message)
            ->send();
    }
}
