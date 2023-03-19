<?php

namespace Modules\Wallet\Enums;

enum WalletPermission: string
{
    case All = 'wallet.*';
    case ViewAny = 'wallet.viewAny';
    case ViewOwned = 'wallet.viewOwned';
    case View = 'wallet.view';
    case Update = 'wallet.update';
    case Consume = 'wallet.consume';
    case Purchase = 'wallet.purchase';
    case Transfer = 'wallet.transfer';
}
