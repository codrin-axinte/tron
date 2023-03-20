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

        $plans = PricingPlan::query()->ordered()->get();
        $currentPlan = $user->subscribedPlan();
        $message = '';
        $renderer = app(PricingPlanRenderer::class);

        $this->chat->action(ChatActions::TYPING)->send();

        foreach ($plans as $plan) {
            $message .= $renderer->render($plan, $currentPlan);
            $message .= "\n\n";
        }

        $keyboard = $this->plansMenu($plans, $currentPlan);

        $this->chat
            ->markdown($message)
            ->keyboard($keyboard)
            ->dispatch();
    }

    protected function plansMenu($plans, $currentPlan): Keyboard
    {

        $buttons = $plans->map(
            fn($plan) => $plan->id !== $currentPlan?->id ? Button::make("➡️ Change to " . $plan->title)
                ->action('upgrade')
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
