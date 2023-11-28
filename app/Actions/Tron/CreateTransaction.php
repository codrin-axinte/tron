<?php

namespace App\Actions\Tron;

use App\Enums\TransactionStatus;
use App\Events\TransactionApproved;
use App\Events\TransactionRejected;
use App\Events\TransactionStatusUpdated;
use App\Http\Integrations\Tron\Data\TransactionData;
use App\Models\TronTransaction;
use App\ValueObjects\USDT;
use Illuminate\Database\Eloquent\Model;

class CreateTransaction
{

    public function __invoke(TransactionData $transactionData): Model|TronTransaction
    {
        return $this->run($transactionData);
    }

    public function run(TransactionData $transactionData): Model|TronTransaction
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
