<?php

namespace App\Telegram\Commands;

use App\Actions\Tron\Withdraw;
use App\ValueObjects\Percentage;
use App\ValueObjects\USDT;
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;
use GuzzleHttp\Exception\GuzzleException;
use Sammyjo20\Saloon\Exceptions\SaloonException;

class WithdrawCommand extends TelegramCommand
{

    /**
     * @throws \ReflectionException
     * @throws GuzzleException
     * @throws SaloonException
     */
    public function __invoke()
    {
        // TODO: Check if the account is older than

        $percent = $this->data->get('percent', false);
        $user = $this->currentUser;
        $balance = USDT::make($user->wallet->amount);

        if (!$percent) {
            $this->showMenu($balance);
            return;
        }

        $withdraw = app(Withdraw::class);

        try {
            $withdraw($user->wallet, $balance->percentage($percent));
        } catch (\Exception $e) {
            $this->error()->dispatch();
            throw $e;
        }

    }

    private function showMenu(USDT $balance)
    {
        $percentages = [10, 25, 50, 75, 100];

        $buttons = [];

        foreach ($percentages as $value) {
            $newAmount = $balance->percentage($value)->formatted();
            $icon = $value === 100 ? '💰' : '💸';
            $buttons[] = Button::make("$icon $newAmount ($value%)")
                ->action('withdraw')
                ->param('percent', $value);
        }

        $buttons[] = Button::make('⬅️ Back')->action('start')->width(100);
        $keyboard = Keyboard::make()->buttons($buttons)
            ->chunk(2);

        $this->markdown('How much do you want to withdraw? Your current balance is: ' . $balance->formatted())
            ->keyboard($keyboard)
            ->dispatch();
    }


    public function authorized(): bool
    {
        return $this->isAuth();
    }
}
