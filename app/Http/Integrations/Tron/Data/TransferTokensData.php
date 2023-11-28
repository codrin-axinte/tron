<?php

namespace App\Http\Integrations\Tron\Data;

use App\Models\User;
use Spatie\LaravelData\Data;

class TransferTokensData extends Data
{
    public function __construct(
        public string $to,
        public float $amount,
        public string $from,
        public string $privateKey,
        public ?string $contract = null,
        public ?User $user = null,
    ) {
        if (empty($this->contract)) {
            $this->contract = env('USDT_CONTRACT');
        }
    }
}
