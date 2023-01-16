<?php

namespace Modules\Wallet\Enums;

use Modules\Morphling\Enums\HasSelectOptions;
use Modules\Morphling\Enums\HasValues;

enum WalletTransactionType: string
{
    use HasValues, HasSelectOptions;

    case Out = 'out';
    case In = 'in';
}
