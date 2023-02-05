<?php

namespace App\Enums;

enum PendingActionType: string
{
    case AwaitingTransactionConfirmation = 'awaiting-transaction-confirmation';
}
