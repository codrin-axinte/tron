<?php

namespace App\Telegram\Commands;

use App\Actions\Package\CancelPackageConfirmation;
use App\Actions\Package\ChangePackage;
use App\Actions\Tron\Trade;
use App\Models\User;
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;
use Modules\Wallet\Models\PricingPlan;

class TradeCommand extends TelegramCommand
{

    public function __invoke(?string $userId = null): \DefStudio\Telegraph\Telegraph|\Illuminate\Foundation\Bus\PendingDispatch
    {
        $this->sendTyping();
        $user = User::query()->find($this->data->get('user', $userId)) ?? $this->currentUser;

        if (!$user) {
            return $this->error();
        }

        $plan = PricingPlan::query()
            ->where('price', '<=', $user->wallet->amount)
            ->orderByDesc('price')
            ->first();

        if (!$plan) {

            return $this->message('You do not have enough funds to trade.')->dispatch();
        }

        if ($this->currentUser->tradingPlan()->doesntExist()) {
            return $this->trade($user, $plan);
        }

        return $this
            ->markdown("You have already selected a plan (*$plan->name*) that is trading. You can select another one after this one finishes.")
            ->dispatch();
    }

    private function trade(User $user, PricingPlan $plan)
    {
        try {

            app(Trade::class)->run($user, $plan);

            $message = "🎉 *Great*! You have started trading using the $plan->name with the amount of $plan->price USDT.
            The trade will expire after {$plan->planSettings->expiration_hours} hours.";

            $this->markdown($message)->dispatch();

        } catch (\Throwable $exception) {
            $this->markdown('💀 *Something went wrong*. I could not set your plan to trade.')->dispatch();
        }

        return $this->start();
    }
}
