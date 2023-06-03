<?php

namespace App\Models;

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
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
        'meta' => 'array',
    ];
}
