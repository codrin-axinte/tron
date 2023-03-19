<?php

namespace Modules\Morphling\Utils;

use Modules\Morphling\Traits\TableHelper;

/**
 * @method static string modules();
 */
class Table
{
    use TableHelper;

    public static $configPath = 'morphling.table_prefix';
}
