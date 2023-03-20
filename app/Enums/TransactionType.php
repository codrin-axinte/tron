<?php

namespace App\Enums;

enum TransactionType: string
{
    case Out = 'out';
    case In = 'in';
}
