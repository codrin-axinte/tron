<?php

namespace App\Http\Integrations\Tron\Data\Responses;

use Carbon\Carbon;

class GetAccountResponseData extends \Spatie\LaravelData\Data
{
    public function __construct(
            public string $address,
            public int $balance,
            public int $create_time,
            public int $latest_opration_time,

    )
    {
    }

    /**
     * {
    "address": "411b51ed3f22cec14b4c53abb99c4f5bff1aa5b5d1",
    "balance": 7991050830,
    "create_time": 1655784828000,
    "latest_opration_time": 1675564821000,
    "free_net_usage": 1375,
    "latest_consume_free_time": 1675557675000,
    "account_resource": {},
    "owner_permission": {
    "permission_name": "owner",
    "threshold": 1,
    "keys": [
    {
    "address": "411b51ed3f22cec14b4c53abb99c4f5bff1aa5b5d1",
    "weight": 1
    }
    ]
    },
    "active_permission": [
    {
    "type": "Active",
    "id": 2,
    "permission_name": "active",
    "threshold": 1,
    "operations": "7fff1fc0033e0300000000000000000000000000000000000000000000000000",
    "keys": [
    {
    "address": "411b51ed3f22cec14b4c53abb99c4f5bff1aa5b5d1",
    "weight": 1
    }
    ]
    }
    ],
    "frozenV2": [
    {},
    {
    "type": "ENERGY"
    },
    {
    "type": "TRON_POWER"
    }
    ],
    "asset_optimized": true
    }
     */
}
