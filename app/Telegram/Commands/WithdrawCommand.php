<?php

namespace App\Telegram\Commands;

use App\ValueObjects\USDT;
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;

class WithdrawCommand extends TelegramCommand
{
    public function __invoke()
    {
        $user = $this->currentUser;
        $amount = USDT::make($user->wallet->amount);

        $this->markdown('How much do you want to withdraw? Your balance is: '.$amount)
            ->keyboard(Keyboard::make()->buttons([
                Button::make('10%'),
                Button::make('25%'),
                Button::make('50%'),
                Button::make('75%'),
                Button::make('100%'),
            ]))
            ->dispatch();
    }

    public function authorized(): bool
    {
        return $this->isAuth();
    }
}
