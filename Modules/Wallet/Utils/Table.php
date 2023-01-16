<?php

namespace Modules\Wallet\Utils;

use Modules\Morphling\Traits\TableHelper;

/**
 * @method static wallets(string $column = null)
 * @method static creditsPlans(string $column = null)
 * @method static walletTransactions(string $column = null)
 */
class Table
{
    use TableHelper;

    public static string $configPath = 'wallet.table_prefix';
}
