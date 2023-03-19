<?php

namespace Modules\Wallet\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Wallet\Enums\WalletTransactionType;
use Modules\Wallet\Utils\Table;

class WalletTransaction extends Model
{
    protected $fillable = [
        'user_id',
        'wallet_id',
        'amount',
        'type',
        'description',
        'meta',
    ];

    protected $casts = [
        'amount' => 'int',
        'type' => WalletTransactionType::class,
        'meta' => 'array',
    ];

    public function getTable(): string
    {
        return Table::walletTransactions();
    }

    public function author(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function wallet(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }
}
