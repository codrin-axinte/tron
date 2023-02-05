<?php

namespace App\Http\Integrations\Tron\Requests;

use App\Http\Integrations\Tron\Data\TransferTokensData;
use App\Http\Integrations\Tron\TronConnector;
use Sammyjo20\Saloon\Constants\Saloon;
use Sammyjo20\Saloon\Http\SaloonRequest;

class TransferTokens extends SaloonRequest
{
    /**
     * The connector class.
     *
     * @var string|null
     */
    protected ?string $connector = TronConnector::class;

    /**
     * The HTTP verb the request will use.
     *
     * @var string|null
     */
    protected ?string $method = Saloon::POST;

    public function __construct(protected TransferTokensData $data)
    {
    }

    /**
     * The endpoint of the request.
     *
     * @return string
     */
    public function defineEndpoint(): string
    {
        return '/api/transfer';
    }

    public function defaultData(): array
    {
        return $this->data->toArray();
    }
}
