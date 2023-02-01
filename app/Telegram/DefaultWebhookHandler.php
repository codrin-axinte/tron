<?php

namespace App\Telegram;

use DefStudio\Telegraph\Keyboard\ReplyButton;
use DefStudio\Telegraph\Keyboard\ReplyKeyboard;
use Illuminate\Support\Stringable;
use DefStudio\Telegraph\Handlers\WebhookHandler;
use DefStudio\Telegraph\DTO\User;

use DefStudio\Telegraph\Keyboard\Keyboard;
use DefStudio\Telegraph\Keyboard\Button;

class DefaultWebhookHandler extends WebhookHandler
{
    public function start()
    {
        $response = $this->chat->markdown("*hi*")->send();


        if ($response == "Failed") {
            $response->dump();
        }
    }

    public function dismiss()
    {
        $this->deleteKeyboard();
    }

    public function join()
    {
        $response = $this->chat->message('What is your invitation code?')
            ->replyKeyboard(
                ReplyKeyboard::make()->button('Text'),
            )->send();


        $this->chat->message('received')->removeReplyKeyboard()->send();
       /* if ($code == "404") {
            $this->chat->markdown("Verified")->send();
        } else {
            $this->chat->markdown("Not good")->send();
        }*/
    }

    public function test()
    {
        $this->chat->message('hello world')
            ->keyboard(Keyboard::make()->buttons([
                Button::make('open')->url('https://test.it'),
            ]))->send();
    }
}
