<?php

namespace App\Telegram\Commands;

use App\Enums\ChatHooks;
use App\Telegram\Traits\HasChatMenus;
use App\Telegram\Traits\HasMessageTemplates;

class StartCommand extends TelegramCommand
{
    use HasMessageTemplates, HasChatMenus;

    public function __invoke()
    {
        if ($this->isAuth()) {
            $this->showMenu();
            return;
        }

        // Show message template for onboarding
        $this->sendTemplate(ChatHooks::Start);
    }
}
