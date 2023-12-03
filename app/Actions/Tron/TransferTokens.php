<?php

namespace App\Actions\Tron;

use App\Events\TokenTransferFailed;
use App\Events\TokenTransferSuccessful;
use App\Exceptions\TronNumericFaultException;
use App\Http\Integrations\Tron\Data\TransactionData;
use App\Http\Integrations\Tron\Data\TransferTokensData;
use App\Http\Integrations\Tron\Requests\TRC20\TransferUsdtRequest;
use App\Models\TronTransaction;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Model;
use Log;
use ReflectionException;
use Sammyjo20\Saloon\Exceptions\SaloonException;
use Throwable;

class TransferTokens
{
    public function __construct(protected CreateTransaction $createTransaction)
    {
    }

    /**
     * @param TransferTokensData $data
     * @return TronTransaction|null
     */
    public function run(TransferTokensData $data): ?TronTransaction
    {
        $transaction = null;
        try {
            $transaction = $this->transfer($data);
            event(new TokenTransferSuccessful($transaction));
        } catch (Throwable $e) {
            Log::error($e);
            event(new TokenTransferFailed($transaction, $data, $e->getMessage()));
        }

        return $transaction;
    }

    /**
     * @throws ReflectionException
     * @throws GuzzleException
     * @throws SaloonException
     * @throws Exception
     */
    private function transfer(TransferTokensData $data): Model|TronTransaction|null
    {
        $response = TransferUsdtRequest::make($data->except('user'))->send();
        $reference = $response->json();
        if ($response->status() >= 400) {
            Log::error('Transfer failed', ['response' => $response->json()]);
            throw new Exception('Transfer failed');
        }

        if (is_array($reference)) {
            if ($reference['code'] === 'NUMERIC_FAULT') {
                throw new TronNumericFaultException;
            }

            throw new Exception(json_encode($reference));
        }

        $transactionData = new TransactionData($data, referenceId: $reference);

        return $this->createTransaction->run($transactionData);
    }


    /**
     * @param TransferTokensData $data
     * @return TronTransaction|null
     */
    public function __invoke(TransferTokensData $data): ?TronTransaction
    {
        return $this->run($data);
    }
}
