<?php

namespace App\Http\Integrations\Tron;

use Sammyjo20\Saloon\Http\SaloonConnector;
use Sammyjo20\Saloon\Traits\Plugins\AcceptsJson;
use Sammyjo20\Saloon\Traits\Plugins\HasJsonBody;

class TronConnector extends SaloonConnector
{
    use AcceptsJson, HasJsonBody;

    /**
     * The Base URL of the API.
     */
    public function defineBaseUrl(): string
    {
        return config('services.tron.url');
    }

    /**
     * The headers that will be applied to every request.
     *
     * @return string[]
     */
    public function defaultHeaders(): array
    {
        return [];
    }

    /**
     * The config options that will be applied to every request.
     *
     * @return string[]
     */
    public function defaultConfig(): array
    {
        return [];
    }
}
