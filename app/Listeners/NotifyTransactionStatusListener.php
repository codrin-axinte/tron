<?php

namespace App\Listeners;

use App\Events\TokenTransferFailed;
use App\Notifications\TokenTransferFailedNotification;
use App\Notifications\TransactionStatusNotification;

class NotifyTransactionStatusListener
{
    public function __construct()
    {
    }

    public function handle(TokenTransferFailed $event): void
    {
        $user = $event->data->user;

        if(!$user) {
            return;
        }

        $user->notify(new TokenTransferFailedNotification());
    }
}
