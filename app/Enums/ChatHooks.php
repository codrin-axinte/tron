<?php

namespace App\Enums;

use Modules\Morphling\Enums\HasSelectOptions;
use Modules\Morphling\Enums\HasValues;

enum ChatHooks: string
{
    use HasValues, HasSelectOptions;

    case Start = 'start';
    case Joined = 'joined';
    case Activated = 'activated';
    case WalletUpdated = 'wallet::updated';
    case Help = 'help';

    case TradingFinished = 'trading::finished';
    case TradingStarted = 'trading::started';

    case TransactionApproved = 'transaction::approved';
    case TransactionRejected = 'transaction::rejected';
    case TransactionPending = 'transaction::pending';
}
