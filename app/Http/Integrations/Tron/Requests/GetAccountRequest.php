<?php

namespace App\Http\Integrations\Tron\Requests;

use App\Http\Integrations\Tron\TronConnector;
use Sammyjo20\Saloon\Constants\Saloon;
use Sammyjo20\Saloon\Http\SaloonRequest;

class GetAccountRequest extends SaloonRequest
{
    /**
     * The connector class.
     */
    protected ?string $connector = TronConnector::class;

    /**
     * The HTTP verb the request will use.
     */
    protected ?string $method = Saloon::GET;

    public function __construct(public string $address)
    {

    }

    /**
     * The endpoint of the request.
     */
    public function defineEndpoint(): string
    {
        return '/api/accounts/'.$this->address;
    }
}
