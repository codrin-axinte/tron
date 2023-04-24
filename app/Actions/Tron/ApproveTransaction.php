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
     * @throws \Exception
     */
    public function __invoke(TronTransaction $transaction): TronTransaction
    {
        // FIXME: Error Numeric fault
        //   reason: 'underflow',
        //  code: 'NUMERIC_FAULT',
        //  fault: 'underflow',
        //  operation: 'BigNumber.from',
        //  value: 22376139000.000004
        //}

        $response = TransferTokensRequest::make(
            TransferTokensData::from($transaction->meta['payload'])
        )->send();

        $data = $response->json();

        if (is_array($data)) {
            if ($data['code'] === 'NUMERIC_FAULT') {
                throw new \Exception('Transfer failed');
            }
        }

        $transaction->status = TransactionStatus::Approved;
        $transaction->blockchain_reference_id = $response->json();
        $transaction->save();

        return $transaction;
    }
}
