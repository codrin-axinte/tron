<?php

namespace App\Listeners;

use App\Actions\MLM\CommissionPayment;
use App\Actions\Tron\UserActivateAction;
use App\Events\BlockchainTopUp;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
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
        private UserActivateAction $action,
        private CommissionPayment  $commissionPayment,
        private SettingsService    $settings
    )
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param BlockchainTopUp $event
     * @return void
     * @throws \Throwable
     */
    public function handle(BlockchainTopUp $event): void
    {
        $user = $event->user;

        if ($user->hasAnyRole([AclService::trader()])) {
            return;
        }

        \DB::transaction(function () use ($user, $event) {
            $this->action->run($user);
            $this->payCommissions($user, $event->amount);
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
