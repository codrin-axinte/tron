<?php

namespace Modules\Wallet\Enums;

use Modules\Morphling\Enums\HasSelectOptions;
use Modules\Morphling\Enums\HasValues;

enum WalletTransactionType: string
{
    use HasValues, HasSelectOptions;

    case Pending = 'pending';
    case Cancelled = 'cancelled';
    case Expired = 'expired';
    case Out = 'out';
    case In = 'in';
}
