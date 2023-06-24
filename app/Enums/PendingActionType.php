<?php

namespace App\Enums;

enum PendingActionType: string
{
    case Withdraw = 'withdraw';
    case Trade = 'trade';
}
