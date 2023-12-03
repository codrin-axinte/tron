<?php

namespace App\Http\Integrations\Tron\Requests\TRC20;

use App\Http\Integrations\Tron\Data\TransferTokensData;
use App\Http\Integrations\Tron\TronConnector;
use Sammyjo20\Saloon\Constants\Saloon;
use Sammyjo20\Saloon\Http\SaloonRequest;
use Sammyjo20\Saloon\Traits\Plugins\HasJsonBody;

class TransferUsdtRequest extends SaloonRequest
{
    use HasJsonBody;

    /**
     * The connector class.
     */
    protected ?string $connector = TronConnector::class;

    /**
     * The HTTP verb the request will use.
     */
    protected ?string $method = Saloon::POST;

    public function __construct(protected ?TransferTokensData $data = null)
    {
    }

    /**
     * The endpoint of the request.
     */
    public function defineEndpoint(): string
    {
        return '/api/trc20/transfer';
    }

    public function defaultData(): array
    {
        return $this->data?->toArray() ?? [];
    }
}
