<?php

namespace App\Telegram\Commands;

use App\Telegram\Renderers\PricingPlanRenderer;
use DefStudio\Telegraph\Enums\ChatActions;
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;
use Modules\Wallet\Models\PricingPlan;

class PackagesCommand extends TelegramCommand
{
    public function __invoke()
    {
        $user = $this->currentUser;
        if (!$user) {
            return $this->error();
        }

        $this->sendTyping();

        $tradingPlan = $user->tradingPlan;

        if ($tradingPlan) {
            return $this->chat
                ->markdown('You are already trading. Please, wait to finish the current trading in order to trade again.')
                ->dispatch();
        }

        $plans = PricingPlan::query()->ordered()->get();

        $message = $this->makeMessage($plans);

        //$keyboard = $this->plansMenu($plans, $currentPlan);
        $keyboard = $this->confirmMenu($user);

        $this->chat
            ->markdown($message)
            ->keyboard($keyboard)
            ->dispatch();
    }

    protected function makeMessage($plans)
    {
        // $currentPlan = $user->subscribedPlan();
        $message = '';
        $currentPlan = null;
        $renderer = app(PricingPlanRenderer::class);
        foreach ($plans as $plan) {
            $message .= $renderer->render($plan, $currentPlan);
            $message .= "\n\n";
        }

        return $message;
    }

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

    protected function plansMenu($plans, $currentPlan): Keyboard
    {

        $buttons = $plans->map(
            fn($plan) => $plan->id !== $currentPlan?->id ? Button::make("➡️ Trade using " . $plan->title)
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
