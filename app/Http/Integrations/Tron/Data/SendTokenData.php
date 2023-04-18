<?php

namespace App\Http\Integrations\Tron\Data;

use Spatie\LaravelData\Data;

class SendTokenData extends Data
{
    public function __construct(
        public string $to,
        public string $from,
        public int|float $amount,
        public string $tokenId
    ) {
    }
}
