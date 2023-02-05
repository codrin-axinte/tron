<?php

namespace App\Http\Integrations\Tron\Data;

use Spatie\LaravelData\Attributes\MapOutputName;

class GenerateWalletResponseData extends \Spatie\LaravelData\Data
{
    public function __construct(
        public string $privateKey,
        public string $publicKey,
        public string $address,
        public array $mnemonic
    )
    {
    }
}
