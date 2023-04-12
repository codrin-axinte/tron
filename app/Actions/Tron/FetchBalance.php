<?php

namespace App\Actions\Tron;

use App\Http\Integrations\Tron\Requests\TRC20\GetAccountBalanceRequest;
use GuzzleHttp\Exception\GuzzleException;
use Sammyjo20\Saloon\Exceptions\SaloonException;

class FetchBalance
{
    public function __construct(protected GetAccountBalanceRequest $request)
    {
    }

    /**
     * @throws \ReflectionException
     * @throws GuzzleException
     * @throws SaloonException
     */
    public function __invoke(string $address)
    {
        return $this->request
            ->setData(compact('address'))
            ->send()
            ->json();
    }
}
