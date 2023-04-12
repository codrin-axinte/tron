<?php

namespace App\Actions\Tron;

use App\Http\Integrations\Tron\Data\Responses\GenerateWalletResponseData;
use App\Http\Integrations\Tron\Requests\GenerateRandomWalletRequest;
use GuzzleHttp\Exception\GuzzleException;
use Sammyjo20\Saloon\Exceptions\SaloonException;

class GenerateWallet
{
    public function __construct(protected GenerateRandomWalletRequest $request)
    {

    }

    /**
     * @throws \ReflectionException
     * @throws GuzzleException
     * @throws SaloonException
     */
    public function __invoke(): GenerateWalletResponseData
    {
        return GenerateWalletResponseData::from($this->request->send()->json());
    }
}
