<?php

namespace App\Telegram\Traits;

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

        $this->chat->message("Wallet: {$wallet->amount} USD")->keyboard($menu)->send();

        return $this;
    }


    protected function plansMenu($plans, $currentPlan): Keyboard
    {

        $buttons = $plans->map(
            fn($plan) => $plan->id !== $currentPlan?->id ? Button::make("âĄī¸ Change to " . $plan->title)
                ->action('upgrade')
                ->param('package', $plan->id) : null
        )->filter()->toArray();

        return Keyboard::make()
            ->buttons(
                [
                    ...$buttons,
                    Button::make('âŦī¸ Back')->action('start'),
                ]
            );
    }

    private function mainMenu(): Keyboard
    {
        return Keyboard::make()
            ->buttons([
                Button::make('đŗ Deposit')->action('wallet'),
            ])
            ->when($this->currentUser->hasRole('trader'), function (Keyboard $keyboard) {
                return $keyboard
                    ->buttons([
                        Button::make('đĩ Withdraw')->action('dummy'),
                        Button::make('đ Referral code')->action('myCode'),
                        Button::make('đĨ My team')->action('team'),
                        Button::make('đ Leaderboard')->action('dummy'),
                       // Button::make('đ Stats')->action('dummy'),
                    ]);
            })
            ->buttons([
              //  Button::make('âĄ Upgrade package')->action('packages'),
                Button::make('âšī¸ Support')->action('help'),
            ])
            ->when(
                $this->currentUser->can(GenericPermission::ViewAdmin->value),
                fn(Keyboard $keyboard) => $keyboard->button('đ ī¸ Admin')->action('admin')
            )->chunk(2);
    }
}
