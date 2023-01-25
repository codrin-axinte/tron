<?php

namespace App\Telegram;

use Illuminate\Support\Stringable;
use DefStudio\Telegraph\Handlers\WebhookHandler;
use DefStudio\Telegraph\DTO\User;

use DefStudio\Telegraph\Keyboard\Keyboard;
use DefStudio\Telegraph\Keyboard\Button;

class DefaultWebhookHandler extends WebhookHandler
{
    public function start()
    {
        $reponse = $this->chat->markdown("*hi*")->send();
        if ($response == "Failed")
        {
            $response->dump();
        }
    }
    public function join($code)
    {
        if ($text == "404")
        {
            $this->chat->markdown("Verified")->send();
        }
        else
        {
            $this->chat->markdown("Not good")->send();
        }
    }
    public function test()
    {
        $this->chat->message('hello world')
    ->keyboard(Keyboard::make()->buttons([
            Button::make('open')->url('https://test.it'),
    ]))->send();
    }
}