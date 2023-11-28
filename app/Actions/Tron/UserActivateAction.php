<?php

namespace App\Actions\Tron;

use App\Actions\MLM\CommissionPayment;
use App\Enums\ChatHooks;
use App\Events\TelegramHook;
use App\Models\User;
use Modules\Acl\Services\AclService;
use Modules\Settings\Services\SettingsService;
use Modules\Wallet\Models\PricingPlan;
use Modules\Wallet\Models\Wallet;

class UserActivateAction
{
    public function run(User $user): void
    {
        $plan = PricingPlan::query()
            ->enabled()
            ->orderBy('price')
            ->first();

        $wallet = $user->wallet;

        if ($wallet->amount < $plan->price) {
            // We wait for more money. Should send a telegram message.
            $diff = $plan->price - $wallet->amount;
            $user->chat
                ->markdown('You have to deposit at least ' . $diff . ' USDT in order to activate your account. You have ' . $wallet->amount . ' USDT in your wallet.')
                ->send();

            return;
        }

        $user->assignRole(AclService::trader());
        event(new TelegramHook($user, ChatHooks::Activated));
    }
}
