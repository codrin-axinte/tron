<?php

namespace App\Http\Integrations\Tron\Data;

use App\ValueObjects\USDT;
use Spatie\LaravelData\Data;

class TransferTrxData extends Data
{
    public function __construct(
        public string $to,
        public USDT $amount,
        public string $privateKey,
    ) {
    }
}
