<?php

namespace App\Models;

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Http\Integrations\Tron\Data\TransferTokensData;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class TronTransaction extends Model
{
    use HasUuids;

    protected $guarded = [];

    protected $casts = [
        'status' => TransactionStatus::class,
        'type' => TransactionType::class,
        'amount' => 'float',
    ];


    public function toData()
    {
        return new TransferTokensData(
            to: $this->to,
            amount: $this->amount,

        );
    }
}
