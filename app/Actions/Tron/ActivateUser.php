<?php

namespace App\Actions\Tron;

use App\Enums\ChatHooks;
use App\Events\TelegramHook;
use App\Models\User;
use Modules\Acl\Services\AclService;
use Modules\Wallet\Models\PricingPlan;

class ActivateUser
{
    public function activate(User $user): void
    {
        $plan = PricingPlan::query()->enabled()->orderBy('price')->first();
        $wallet = $user->wallet;

        if ($wallet->amount < $plan->price) {
            // We wait for more money. Should send a telegram message.
            $diff = $plan->price - $wallet->amount;
            $user->chat
                ->markown('You have to deposit at least '.$diff.' USDT in order to activate your account. You have '.$wallet->amount.' USDT in your wallet.')
                ->send();

            return;
        }

        $user->assignRole(AclService::trader());
        event(new TelegramHook($user, ChatHooks::Activated));
    }
}
