<?php

namespace App\Telegram\Commands;

class DummyCommand extends TelegramCommand
{
    public function __invoke()
    {
        $this->chat->message('💀 Not implemented yet.')->send();
        $this->start();
    }
}
