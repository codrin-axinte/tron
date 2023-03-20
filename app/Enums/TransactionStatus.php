<?php

namespace App\Enums;

enum TransactionStatus: string
{

    case Rejected = 'rejected';
    case Retry = 'retry';
    case AwaitingConfirmation = 'pending';
    case Approved = 'confirmed';
}
