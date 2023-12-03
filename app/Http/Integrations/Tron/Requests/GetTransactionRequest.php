<?php

namespace App\Http\Integrations\Tron\Requests;

use Sammyjo20\Saloon\Constants\Saloon;
use Sammyjo20\Saloon\Http\SaloonRequest;

class GetTransactionRequest extends SaloonRequest
{
    /**
     * The connector class.
     *
     * @var string|null
     */
    protected ?string $connector = null;

    /**
     * The HTTP verb the request will use.
     *
     * @var string|null
     */
    protected ?string $method = Saloon::GET;

    /**
     * The endpoint of the request.
     *
     * @return string
     */
    public function defineEndpoint(): string
    {
        return '/api/v1/user';
    }
}
