<?php

namespace App\Http\Integrations\Tron\Data;

use Spatie\LaravelData\Data;

class CreateAccountData extends Data
{
    public function __construct(
        public string $address
    ) {
    }
}
