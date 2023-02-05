<?php

namespace App\Http\Integrations\Tron\Data\Responses;

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
