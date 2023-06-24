<?php

namespace App\Enums;

enum TransactionType: string
{
    /**
     * @deprecated
     */
    case Out = 'out';
    /**
     * @deprecated
     */
    case In = 'in';

    case Withdraw = 'withdraw';
    case Deposit = 'deposit';
}
