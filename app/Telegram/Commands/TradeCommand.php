<?php

namespace App\Telegram\Commands;

use App\Actions\Tron\Trade;
use App\Models\User;
use App\Telegram\Traits\HasChatMenus;
use App\ValueObjects\USDT;
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;
use Modules\Wallet\Models\PricingPlan;

class TradeCommand extends TelegramCommand
{
    public function __invoke(?string $userId = null)
    {
        $percent = $this->data->get('percent', false);

        if (!$percent) {
            return $this->error(__("You must select an amount to trade."))
                ->dispatch();
        }

        $this->sendTyping();
        $user = User::query()
            ->find($this->data->get('user', $userId)) ?? $this->currentUser;

        if (!$user) {
            return $this->error()->dispatch();
        }

        $balance = USDT::make($user->wallet->amount);

        $plan = PricingPlan::query()
            ->highestPlan(
                $balance->percentage($percent)
            )
            ->first();

        try {
            $this->canTrade($user, $plan);
            return $this->trade($user, $plan);
        } catch (\Exception $exception) {
            return $this
                ->error($exception->getMessage())
                ->dispatch();
        }
    }

    /**
     * @throws \Exception
     */
    private function canTrade($user, $plan): void
    {
        if (!$plan) {
            throw new \Exception(__('You do not have enough funds to trade.'));
        }

        if ($user->tradingPlan()->exists()) {
            throw new \Exception(
                __("You have already selected a plan (:plan) that is trading. You can select another one after this one finishes.", ['plan' => $plan->name])
            );
        }
    }

    private function trade(User $user, PricingPlan $plan)
    {
        try {

            app(Trade::class)->run($user, $plan);

            $message = __("*Great*! You have started trading using the :plan package. The trade will expire after :hours hours.",
                ['plan' => $plan->name, 'hours' => $plan->planSettings->expiration_hours]);

            $this->success($message)->dispatch();

        } catch (\Throwable $exception) {
            $this->error(__('*Something went wrong*. I could not set your plan to trade.'))
                ->dispatch();
        }

        return $this->start();
    }
}
