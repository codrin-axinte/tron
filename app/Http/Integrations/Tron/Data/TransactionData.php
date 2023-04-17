<?php

namespace App\Http\Integrations\Tron\Data;

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use Spatie\LaravelData\Data;

class TransactionData extends Data
{
    public function __construct(
        public TransferTokensData $transferData,
        public TransactionStatus  $status = TransactionStatus::Approved,
        public TransactionType    $type = TransactionType::In,
        public ?string            $referenceId = null,
        public ?array             $meta = null
    )
    {

    }
}