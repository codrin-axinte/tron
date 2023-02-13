<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Wallet\Traits\HasWallet;

class Pool extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    protected $casts = [
        'balance' => 'float',
        'is_central' => 'bool',
        'mnemonic' => 'array',
    ];

    public function scopeWhereCentral($query)
    {
        return $query->where('is_central', true);
    }

    public function scopeWhereNotCentral($query)
    {
        return $query->where('is_central', true);
    }
}
