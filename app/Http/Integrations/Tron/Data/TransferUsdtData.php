<?php

namespace App\Http\Integrations\Tron\Data;

use App\Models\User;
use Spatie\LaravelData\Data;

class TransferUsdtData extends Data
{
    public function __construct(
        public string $to,
        public float $amount,
        public string $from,
        public string $privateKey,
        public ?User $user = null,
    ) {
    }
}
