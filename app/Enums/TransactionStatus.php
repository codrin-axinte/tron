<?php

namespace App\Enums;

enum TransactionStatus: string
{
    case Rejected = 'rejected';
    case AwaitingConfirmation = 'pending';
    case Approved = 'approved';
}
