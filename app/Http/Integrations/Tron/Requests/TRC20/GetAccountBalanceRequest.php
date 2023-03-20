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


    /**
     * The endpoint of the request.
     *
     * @return string
     */
    public function defineEndpoint(): string
    {
        return "/api/trc20/balance";
    }
}
