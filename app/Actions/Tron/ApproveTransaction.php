<?php

namespace App\Actions\Tron;

use App\Enums\TransactionStatus;
use App\Http\Integrations\Tron\Data\TransferTokensData;
use App\Http\Integrations\Tron\Requests\TRC20\TransferTokensRequest;
use App\Models\TronTransaction;
use GuzzleHttp\Exception\GuzzleException;
use Sammyjo20\Saloon\Exceptions\SaloonException;

class ApproveTransaction
{
    /**
     * @throws \ReflectionException
     * @throws GuzzleException
     * @throws SaloonException
     */
    public function __invoke(TronTransaction $transaction): TronTransaction
    {

        $response = TransferTokensRequest::make(
            TransferTokensData::from($transaction->meta['payload'])
        )->send();

        $transaction->status = TransactionStatus::Approved;
        $transaction->blockchain_reference_id = $response->json();
        $transaction->save();

        return $transaction;
    }
}
