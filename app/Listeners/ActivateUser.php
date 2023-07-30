<?php

namespace App\Listeners;

use App\Actions\MLM\CommissionPayment;
use App\Actions\Tron\UserActivateAction;
use App\Events\TokenTransferSuccessful;
use Modules\Acl\Services\AclService;
use Modules\Settings\Services\SettingsService;

final class ActivateUser
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(
        private UserActivateAction $activateAction,
        private CommissionPayment  $commissionPayment,
        private SettingsService    $settings
    )
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param TokenTransferSuccessful $event
     * @return void
     * @throws \Throwable
     */
    public function handle(TokenTransferSuccessful $event): void
    {

        $transaction = $event->transaction;
        $user = $transaction->ownerWallet->user;

        if ($user->hasAnyRole([AclService::trader()])) {
            return;
        }

        \DB::transaction(function () use ($user, $transaction) {
            $this->activateAction->run($user);
            $this->payCommissions($user, $transaction->amount);
        });
    }

    private function payCommissions($user, $amount): void
    {
        $commissions = collect($this->settings->getJson('commissions', []))
            ->pluck('percentage')
            ->toArray();

        $this->commissionPayment
            ->forAmount($amount)
            ->withCommissions($commissions)
            ->execute($user);
    }
}
