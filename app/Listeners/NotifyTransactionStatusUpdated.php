<?php

namespace App\Listeners;

use App\Events\TransactionApproved;
use App\Events\TransactionRejected;
use App\Events\TransactionStatusUpdated;
use App\Notifications\TransactionStatusNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyTransactionStatusUpdated
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

    public function handle(TransactionStatusUpdated $event): void
    {
        $transaction = $event->transaction;
        $user = $transaction->ownerWallet->user;

        $user->notify(new TransactionStatusNotification($transaction));
    }
}
