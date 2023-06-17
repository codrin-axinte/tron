<?php

namespace App\Telegram\Commands;

use App\Actions\Tron\Trade;
use App\Enums\MessageType;
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
            return $this->send(__("You must select an amount to trade."), MessageType::Error)
                ->start();
        }

        $user = User::query()
            ->find($this->data->get('user', $userId)) ?? $this->currentUser;

        if (!$user) {
            return $this->send(__('messages.error'), MessageType::Error)->start();
        }

        $balance = USDT::make($user->wallet->amount);

        $plan = PricingPlan::query()
            ->highestPlan(
                $balance->percentage($percent)
            )->first();

        try {
            $this->canTrade($user, $plan);
            return $this->trade($user, $plan);
        } catch (\Exception $exception) {
            return $this
                ->send($exception->getMessage(), MessageType::Error)
                ->start();
        }
    }

    /**
     * @throws \Exception
     */
    private function canTrade($user, $plan): void
    {
        if (!$plan) {
            throw new \Exception(__('trading.insufficient_funds'));
        }

        if ($user->tradingPlan()->exists()) {
            throw new \Exception(__('trading.in_progress', ['plan' => $plan->name]));
        }
    }

    private function trade(User $user, PricingPlan $plan)
    {
        try {

            app(Trade::class)->run($user, $plan);

            $message = __('trading.started',
                ['plan' => $plan->name, 'hours' => $plan->planSettings->expiration_hours]);

            return $this->send($message, MessageType::Success)
                ->start();

        } catch (\Throwable $exception) {
            return $this->send(__('trading.failed'), MessageType::Error)
                ->start();
        }
    }
}
