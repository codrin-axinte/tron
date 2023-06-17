<?php

namespace App\Telegram\Commands;

class MeCommand extends TelegramCommand
{
    public function __invoke()
    {

        $from = $this->message->from();

        $message = "Name: {$from->firstName()} {$from->lastName()}\nUsername: {$from->username()}\nID:{$from->id()}";
        $this->send($message)->start();
    }
}
