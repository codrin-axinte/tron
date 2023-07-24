<?php

namespace App\Listeners;

use App\Actions\MLM\CommissionPayment;
use App\Events\BlockchainTopUp;
use App\Events\UserJoined;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Modules\Settings\Services\SettingsService;

/**
 * @deprecated
 */
class PayCommissions
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(
        protected CommissionPayment $commissionPayment,
        protected SettingsService   $settings
    )
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param BlockchainTopUp $event
     * @return void
     */
    public function handle(BlockchainTopUp $event)
    {
        if ($event->user->hasRole('trader')) {
            return;
        }

        $commissions = collect($this->settings->getJson('commissions', []))
            ->pluck('percentage')
            ->toArray();

        $this->commissionPayment
            ->forAmount($event->amount)
            ->withCommissions($commissions)
            ->execute($event->user);
    }
}
