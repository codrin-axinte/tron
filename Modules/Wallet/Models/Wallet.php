<?php

namespace Modules\Wallet\Models;

use App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Wallet\Traits\HasWalletTransactions;
use Modules\Wallet\Utils\Table;

class Wallet extends Model
{
    use HasFactory, HasWalletTransactions;

    protected $guarded = [];

    protected $casts = [
        'amount' => 'float',
        'blockchain_amount' => 'float',
        'mnemonic' => 'array',
    ];

    public function getTable(): string
    {
        return Table::wallets();
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
