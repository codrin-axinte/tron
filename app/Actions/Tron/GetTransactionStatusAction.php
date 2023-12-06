<?php

namespace App\Actions\Tron;

use Exception;
use App\Enums\TransactionBlockchainStatus;
use App\Http\Integrations\Tron\Requests\GetTransactionRequest;
use App\Models\TronTransaction;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use ReflectionException;
use Sammyjo20\Saloon\Exceptions\SaloonException;

class GetTransactionStatusAction
{
    /**
     * @param TronTransaction $transaction
     * @return TransactionBlockchainStatus
     * @throws GuzzleException
     * @throws ReflectionException
     * @throws SaloonException
     * @throws Exception
     */
    public function __invoke(TronTransaction $transaction): TransactionBlockchainStatus
    {
        if (!$transaction?->blockchain_reference_id) {
            return TransactionBlockchainStatus::Invalid;
        }

        $request = new GetTransactionRequest($transaction->blockchain_reference_id);

        $response = $request->send();
        $data = $response->json();
        // ray($response->status(), $data)->showApp();

        if (empty($data)) {
            return TransactionBlockchainStatus::Invalid;
        }

        if ($response->status() >= Response::HTTP_BAD_REQUEST) {
            Log::error('Error fetching transaction.', [
                'transaction' => $transaction?->blockchain_reference_id,
                'body' => $data,
            ]);

            throw new Exception('Error fetching transaction.');
        }

        if (!isset($data['receipt'])) {
            // If 'receipt' is not present in the response, it indicates that the transaction
            // details are not yet available, thus considering it as 'Pending'.
            return TransactionBlockchainStatus::Pending;
        }

        $receipt = $data['receipt'];

        // Checking the 'result' field in the receipt to determine the transaction status.
        if (isset($receipt['result'])) {
            return match ($receipt['result']) {
                'SUCCESS' => TransactionBlockchainStatus::Success,
                'REVERT' => TransactionBlockchainStatus::Reverted,
                'FAILED' => TransactionBlockchainStatus::Failed,
                default => TransactionBlockchainStatus::Invalid,
            };
        } else if (isset($receipt['energy_usage_total']) && $receipt['energy_usage_total'] > 0) {
            // If 'energy_usage_total' is greater than 0, it implies that the transaction
            // failed due to Out of Energy or Bandwidth.
            return TransactionBlockchainStatus::OOEB;
        }

        // If none of the above conditions are met, the transaction status is marked as Invalid.
        return TransactionBlockchainStatus::Invalid;
    }
}
