<?php

namespace App\Telegram\Commands;

use App\Enums\MessageType;
use App\Telegram\Renderers\PricingPlanRenderer;
use App\Telegram\Traits\HasChatMenus;
use App\ValueObjects\USDT;
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;
use Modules\Wallet\Models\PricingPlan;

class PackagesCommand extends TelegramCommand
{
    use HasChatMenus;

    public function __invoke()
    {
        $user = $this->currentUser;

        if (!$user) {
            return $this->send(__('messages.error'), MessageType::Error)->start();
        }

        $tradingPlan = $user->tradingPlan;

        if ($tradingPlan) {

            return $this->send(__(
                '📈 You are already trading. Please, wait to finish the current trading in order to trade again. Finishes :remaining',
                ['remaining' => $tradingPlan->remainingTime]
            ))->start();
        }

        $plans = PricingPlan::query()->orderBy('price')->get();
        $balance = USDT::make($user->wallet->amount);

        $message = "\n" .
            __('🏦 Your current balance is: *:balance*',
                ['balance' => $balance->formatted()]
            ) . "\n\n";

        $message .= $this->makeMessage($plans);

        $message .= __('🤑 *How much do you want to trade?*') . "\n";

        $keyboard = $this->choosePercentageMenu($balance, 'trade');

        return $this->markdown($message)
            ->keyboard($keyboard)
            ->dispatch('telegram');
    }

    protected function makeMessage($plans): string
    {
        $message = '';
        $currentPlan = null;
        $renderer = app(PricingPlanRenderer::class);
        foreach ($plans as $plan) {
            $message .= $renderer->render($plan, $currentPlan);
            $message .= "\n\n";
        }

        return $message;
    }

    /**
     * @param $user
     * @return Keyboard
     * @deprecated Replaced with percentage menu
     *
     */
    protected function confirmMenu($user): Keyboard
    {
        return Keyboard::make()
            ->buttons([
                Button::make('🤑 Yes, let\'s trade')
                    ->action('trade')
                    ->param('user', $user->id),
                Button::make('⬅️ No, I am still thinking')->action('start'),
            ]);
    }

    /**
     * @param $plans
     * @param $currentPlan
     * @return Keyboard
     * @deprecated replaced with percentage menu
     *
     */
    protected function plansMenu($plans, $currentPlan): Keyboard
    {
        $buttons = $plans->map(
            fn($plan) => $plan->id !== $currentPlan?->id ? Button::make('➡️ Trade using ' . $plan->title)
                ->action('trade')
                ->param('package', $plan->id) : null
        )->filter()->toArray();

        return Keyboard::make()
            ->buttons(
                [
                    ...$buttons,
                    Button::make('⬅️ Back')->action('start'),
                ]
            );
    }
}
