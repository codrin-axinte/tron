<?php

namespace App\Http\Integrations\Tron\Data;

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use Spatie\LaravelData\Data;

class TransactionData extends Data
{
    public function __construct(
        public TransferUsdtData  $transferData,
        public TransactionStatus $status = TransactionStatus::Approved,
        public TransactionType   $type = TransactionType::Deposit,
        public ?string           $referenceId = null,
        public ?array            $meta = null
    )
    {

    }
}
