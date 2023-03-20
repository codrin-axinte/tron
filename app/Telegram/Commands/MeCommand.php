<?php

namespace App\Telegram\Commands;

class MeCommand extends TelegramCommand
{
    public function __invoke()
    {

        $from = $this->message->from();

        $this->message("Name: {$from->firstName()} {$from->lastName()}\nUsername: {$from->username()}\nID:{$from->id()}")->send();
    }
}
