<?php

namespace App\Actions\Tron;

use App\Http\Integrations\Tron\Data\TransactionData;
use App\Models\TronTransaction;
use App\ValueObjects\USDT;

class CreateTransaction
{

    public function __invoke(TransactionData $transactionData): \Illuminate\Database\Eloquent\Model|TronTransaction
    {
        return $this->run($transactionData);
    }

    public function run(TransactionData $transactionData): \Illuminate\Database\Eloquent\Model|TronTransaction
    {
        return TronTransaction::create([
            'from' => $transactionData->transferData->from,
            'to' => $transactionData->transferData->to,
            'amount' => USDT::makeFromSun($transactionData->transferData->amount)->value(),
            'blockchain_reference_id' => $transactionData->referenceId,
            'type' => $transactionData->type,
            'status' => $transactionData->status,
            'meta' => $transactionData->meta,
        ]);
    }
}
