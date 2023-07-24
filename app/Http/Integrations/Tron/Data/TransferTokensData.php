<?php

namespace App\Http\Integrations\Tron\Data;

class TransferTokensData extends \Spatie\LaravelData\Data
{
    public function __construct(
        public string $to,
        public float $amount,
        public string $from,
        public string $privateKey,
        public ?string $contract = null,
    ) {
        if (empty($this->contract)) {
            $this->contract = env('USDT_CONTRACT');
        }
    }
}
