<?php

namespace App\Actions\Tron;

use App\Enums\TransactionStatus;
use App\Events\TransactionApproved;
use App\Http\Integrations\Tron\Data\TransferUsdtData;
use App\Http\Integrations\Tron\Requests\TRC20\TransferUsdtRequest;
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

        $response = TransferUsdtRequest::make(
            TransferUsdtData::from($transaction->meta['payload'])
        )->send();

        $data = $response->json();

        if (is_array($data)) {
            if ($data['code'] === 'NUMERIC_FAULT') {
                throw new \Exception('Transfer failed');
            }
        }


        $transaction->blockchain_reference_id = $response->json();
        $transaction->approve();

        return $transaction;
    }
}
