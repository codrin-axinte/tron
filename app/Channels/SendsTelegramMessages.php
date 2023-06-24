<?php

namespace App\Channels;

use App\Contracts\InteractsWithTelegram;
use DefStudio\Telegraph\Telegraph;

interface SendsTelegramMessages
{
    public function toTelegram(InteractsWithTelegram $notifiable): string;
}
