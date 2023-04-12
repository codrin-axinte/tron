<?php

namespace App\Actions\Tron;

use App\Enums\TransactionStatus;
use App\Models\TronTransaction;

class RejectTransaction
{
    public function __invoke(TronTransaction $transaction): TronTransaction
    {
        $transaction->status = TransactionStatus::Rejected;;
        $transaction->save();

        return $transaction;
    }
}
