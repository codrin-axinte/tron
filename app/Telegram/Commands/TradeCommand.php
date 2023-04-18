<?php

namespace App\Telegram\Commands;

use App\Actions\Tron\Trade;
use App\Models\User;
use Modules\Wallet\Models\PricingPlan;

class TradeCommand extends TelegramCommand
{
    public function __invoke(?string $userId = null): \DefStudio\Telegraph\Telegraph|\Illuminate\Foundation\Bus\PendingDispatch
    {
        $this->sendTyping();
        $user = User::query()->find($this->data->get('user', $userId)) ?? $this->currentUser;

        if (! $user) {
            return $this->error()->dispatch();
        }

        $plan = PricingPlan::query()->highestPlan($user->wallet->amount)->first();

        if (! $plan) {
            return $this->message('You do not have enough funds to trade.')->dispatch();
        }

        if ($user->tradingPlan()->doesntExist()) {
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

            $message = "🎉 *Great*! You have started trading using the $plan->name package. The trade will expire after {$plan->planSettings->expiration_hours} hours.";

            $this->markdown($message)->dispatch();

        } catch (\Throwable $exception) {
            $this->markdown('💀 *Something went wrong*. I could not set your plan to trade.')->dispatch();
        }

        return $this->start();
    }
}
