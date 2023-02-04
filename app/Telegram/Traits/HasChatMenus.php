<?php

namespace App\Telegram\Traits;

use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;

trait HasChatMenus
{

    protected function showMenu(): static
    {
        $plan = $this->currentUser->subscribedPlan();

        if (!$plan) {
            $menu = $this->unverifiedMenu();
        } else {
            $menu = $this->mainMenu();
        }

        $wallet = $this->currentUser->wallet;
        $planName = $plan ? $plan->name : 'No Plan';

        $this->chat->message("Wallet: {$wallet->amount} TRX ($planName)")->keyboard($menu)->send();

        return $this;
    }


    protected function plansMenu($plans, $currentPlan)
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


    private function unverifiedMenu(): Keyboard
    {
        return Keyboard::make()
            ->buttons([
                Button::make('⚡ Upgrade package')->action('packages'),
                Button::make('ℹ️ Support')->action('help'),
            ]);
    }

    private function mainMenu(): Keyboard
    {
        return Keyboard::make()
            ->row([
                Button::make('💳 Deposit')->action('dummy'),
                Button::make('💵 Withdraw')->action('dummy'),
            ])
            ->row([
                Button::make('⚡ Upgrade package')->action('packages'),
                Button::make('📈 Stats')->action('dummy'),
            ])
            ->row([
                Button::make('🔗 Referral code')->action('myCode'),
                Button::make('👥 My team')->action('team'),
            ])
            ->row([
                //Button::make('👑 Leaderboard')->action('dummy'),
                Button::make('ℹ️ Support')->action('help'),
            ]);
    }
}
