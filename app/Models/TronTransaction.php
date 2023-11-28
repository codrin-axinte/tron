<?php

namespace App\Models;

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Events\TransactionStatusUpdated;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Wallet\Models\Wallet;

/**
 * @property TransactionStatus $status
 */
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

    protected static function booted(): void
    {
        static::updated(function (TronTransaction $transaction) {
            if (!$transaction->isDirty('status')) {
                return;
            }

            $wallet = $transaction->ownerWallet;

            if ($transaction->status === TransactionStatus::Rejected) {
                $wallet?->increment('amount', $transaction->amount);
            }

            event(new TransactionStatusUpdated($transaction));
        });

        static::created(function (TronTransaction $transaction) {
            if (!$transaction->isDirty('status')) {
                return;
            }

            $wallet = $transaction->ownerWallet;

            if (in_array($transaction->status, [
                TransactionStatus::AwaitingConfirmation,
                TransactionStatus::Approved,
            ])) {
                $wallet?->decrement('amount', $transaction->amount);
            }

            event(new TransactionStatusUpdated($transaction));
        });
    }


    public function ownerWallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'from', 'address');
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function approve(): bool
    {
        $this->status = TransactionStatus::Approved;
        return $this->save();
    }

    public function reject(): bool
    {
        $this->status = TransactionStatus::Rejected;
        return $this->save();
    }

    public function retry(): bool
    {
        $this->status = TransactionStatus::Retry;
        return $this->save();
    }
}
