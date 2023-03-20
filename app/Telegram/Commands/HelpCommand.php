<?php

namespace App\Telegram\Commands;

use App\Enums\ChatHooks;
use App\Telegram\Traits\HasMessageTemplates;

class HelpCommand extends TelegramCommand
{
    use HasMessageTemplates;

    public function __invoke()
    {
        $this->sendTemplate(ChatHooks::Help);
        $this->start();
    }
}
