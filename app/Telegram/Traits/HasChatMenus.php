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
            fn($plan) => $plan->id !== $currentPlan?->id ? Button::make('➡️ Change to ' . $plan->title)
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
                Button::make(__('💳 Deposit'))->action('wallet'),
            ])
            ->when($this->currentUser->hasRole('trader'), function (Keyboard $keyboard) {
                return $keyboard
                    ->buttons([
                        Button::make(__('💵 Withdraw'))->action('withdraw'),
                        Button::make(__('🔗 Referral code'))->action('myCode'),
                        Button::make(__('👥 My team'))->action('team'),
                        // Button::make('👑 Leaderboard')->action('dummy'),
                        // Button::make('📈 Stats')->action('dummy'),
                        Button::make(__('⚡ Trade'))->action('packages'),
                    ]);
            })
            ->buttons([
                // Button::make('ℹ️ Support')->action('help'),
            ])
            ->when(
                $this->currentUser->can(GenericPermission::ViewAdmin->value),
                fn(Keyboard $keyboard) => $keyboard->button('🛠️ Admin')->action('admin')
            )->chunk(2);
    }

    protected function choosePercentageMenu(USDT $balance, $action, $percentages = [10, 25, 50, 75, 100]): Keyboard
    {
        $buttons = [];

        foreach ($percentages as $value) {
            $newAmount = $balance->percentage($value)->formatted(0);
            $icon = $value === 100 ? '💰' : '💸';
            $buttons[] = Button::make("$icon $newAmount ($value%)")
                ->action($action)
                ->param('percent', $value);
        }

        $buttons[] = Button::make('⬅️ Back')->action('start')->width(100);

        return Keyboard::make()
            ->buttons($buttons)
            ->chunk(2);
    }
}
