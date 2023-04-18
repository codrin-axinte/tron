<?php

namespace App\Http\Integrations\Tron\Requests;

use App\Http\Integrations\Tron\Data\SendTokenData;
use App\Http\Integrations\Tron\TronConnector;
use Sammyjo20\Saloon\Constants\Saloon;
use Sammyjo20\Saloon\Http\SaloonRequest;

class SendTokensRequest extends SaloonRequest
{
    /**
     * The connector class.
     */
    protected ?string $connector = TronConnector::class;

    /**
     * The HTTP verb the request will use.
     */
    protected ?string $method = Saloon::POST;

    public function __construct(protected SendTokenData $data)
    {
    }

    /**
     * The endpoint of the request.
     */
    public function defineEndpoint(): string
    {
        return '/api/tokens/send';
    }

    public function defaultData(): array
    {
        return $this->data->toArray();
    }
}
