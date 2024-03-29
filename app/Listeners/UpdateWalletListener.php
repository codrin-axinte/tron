<?php

namespace App\Listeners;

use App\Events\TokenTransferSuccessful;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateWalletListener
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
     * @param TokenTransferSuccessful $event
     * @return void
     */
    public function handle(TokenTransferSuccessful $event): void
    {
        $transaction = $event->transaction;

        $wallet = $transaction->ownerWallet;

        if (!$wallet) {
            \Log::debug('Wallet not found', $transaction->toArray());
            return;
        }

        $wallet->decrement('blockchain_amount', $transaction->amount);
        $wallet->increment('amount', $transaction->amount);
    }
}
