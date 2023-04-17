<?php

namespace App\Actions\Tron;

use App\Http\Integrations\Tron\Data\TransactionData;
use App\Http\Integrations\Tron\Data\TransferTokensData;
use App\Http\Integrations\Tron\Requests\TRC20\TransferTokensRequest;
use GuzzleHttp\Exception\GuzzleException;
use Sammyjo20\Saloon\Exceptions\SaloonException;

class TransferTokens
{

    public function __construct(protected CreateTransaction $createTransaction)
    {
    }

    /**
     * @throws \ReflectionException
     * @throws GuzzleException
     * @throws SaloonException
     */
    public function __invoke(TransferTokensData $data): \Illuminate\Database\Eloquent\Model|\App\Models\TronTransaction
    {
        return $this->run($data);
    }

    /**
     * @throws \ReflectionException
     * @throws GuzzleException
     * @throws SaloonException
     * @throws \Exception
     */
    public function run(TransferTokensData $data): \Illuminate\Database\Eloquent\Model|\App\Models\TronTransaction
    {
        $response = TransferTokensRequest::make($data)->send();
        $reference = $response->json();

        if (is_array($reference)) {
            if ($reference['code'] === 'NUMERIC_FAULT') {
                throw new \Exception('Transfer failed');
            }
        }

        $transactionData = new TransactionData($data, referenceId: $reference);
        $createTransaction = $this->createTransaction;

        return $createTransaction($transactionData);
    }
}
