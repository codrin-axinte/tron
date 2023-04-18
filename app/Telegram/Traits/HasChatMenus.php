<?php

namespace App\Telegram\Traits;

use App\ValueObjects\USDT;
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;
use Modules\Acl\Enums\GenericPermission;

trait HasChatMenus
{
    protected function showMenu(): static
    {
        // $plan = $this->currentUser->subscribedPlan();
        $menu = $this->mainMenu();

        $wallet = $this->currentUser->wallet;
        // $planName = $plan ? $plan->name : 'No Plan';
        $balance = USDT::make($wallet->amount);
        $this->chat->message("Wallet: {$balance} USD")->keyboard($menu)->send();

        return $this;
    }

    protected function plansMenu($plans, $currentPlan): Keyboard
    {

        $buttons = $plans->map(
            fn ($plan) => $plan->id !== $currentPlan?->id ? Button::make('➡️ Change to '.$plan->title)
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

    private function mainMenu(): Keyboard
    {
        return Keyboard::make()
            ->buttons([
                Button::make('💳 Deposit')->action('wallet'),
            ])
            ->when($this->currentUser->hasRole('trader'), function (Keyboard $keyboard) {
                return $keyboard
                    ->buttons([
                        Button::make('💵 Withdraw')->action('withdraw'),
                        Button::make('🔗 Referral code')->action('myCode'),
                        Button::make('👥 My team')->action('team'),
                        // Button::make('👑 Leaderboard')->action('dummy'),
                        // Button::make('📈 Stats')->action('dummy'),
                        Button::make('⚡ Trade')->action('packages'),
                    ]);
            })
            ->buttons([
                // Button::make('ℹ️ Support')->action('help'),
            ])
            ->when(
                $this->currentUser->can(GenericPermission::ViewAdmin->value),
                fn (Keyboard $keyboard) => $keyboard->button('🛠️ Admin')->action('admin')
            )->chunk(2);
    }
}
