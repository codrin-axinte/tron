<?php

namespace Modules\Wallet\Listeners;

use Modules\Payments\Events\PaymentSuccess;
use Modules\Payments\Models\OrderItem;
use Modules\Wallet\Actions\DepositCredits;

class OnPaymentSuccessListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(PaymentSuccess $event)
    {
        $user = $event->user;
        $order = $event->order;
        $gateway = $event->gateway;
        $source = 'plata card';

        $creditsAmount = $order->items->sum(
            fn (OrderItem $item) => $item->purchasable_type === 'plans' ? $item->purchasable->amount : 0
        );

        $depositCredits = app(DepositCredits::class);
        $depositCredits($user->wallet, $creditsAmount, $source);
        // TODO: Notify about credit deposit
    }
}
