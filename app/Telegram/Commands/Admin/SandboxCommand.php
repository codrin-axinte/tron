<?php

namespace App\Telegram\Commands\Admin;

use App\Telegram\Commands\TelegramCommand;

class SandboxCommand extends TelegramCommand
{
    public function __invoke()
    {
        // Debug
        //$this->chat->message('Test')->forceReply('Input your pin card')->send();
    }

    public function authorized(): bool
    {
        return app()->environment('local');
    }
}
