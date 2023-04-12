<?php

namespace App\Actions\Tron;

use App\Http\Integrations\Tron\Data\TransactionData;
use App\Models\TronTransaction;

class CreateTransaction
{
    public function __invoke(TransactionData $transactionData)
    {
        return TronTransaction::create([
            'from' => $transactionData->transferData->from,
            'to' => $transactionData->transferData->to,
            'amount' => $transactionData->transferData->amount,
            'blockchain_reference_id' => $transactionData->referenceId,
            'type' => $transactionData->type,
            'status' => $transactionData->status,
            'meta' => $transactionData->meta,
        ]);
    }
}
