<?php

namespace App\Http\Integrations\Tron\Requests\TRC20;

use App\Http\Integrations\Tron\TronConnector;
use Sammyjo20\Saloon\Constants\Saloon;
use Sammyjo20\Saloon\Http\SaloonRequest;
use Sammyjo20\Saloon\Traits\Plugins\HasJsonBody;

class GetAccountBalanceRequest extends SaloonRequest
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

    /**
     * The endpoint of the request.
     */
    public function defineEndpoint(): string
    {
        return '/api/trc20/balance';
    }
}
