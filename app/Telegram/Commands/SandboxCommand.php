<?php

namespace App\Telegram\Commands;

class SandboxCommand extends TelegramCommand
{
    public function __invoke()
    {
        // Debug
        //$this->chat->message('Test')->forceReply('Input your pin card')->send();
    }
}
