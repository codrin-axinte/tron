<?php

namespace Modules\Wallet\Traits;

use Illuminate\Database\Eloquent\Model;
use Modules\Wallet\Models\WalletTransaction;

/**
 * @mixin Model
 */
trait HasWalletTransactions
{
    public static function bootHasActivities(): void
    {
        static::deleting(
            fn ($model) => $model
                ->transactions()
                ->cursor()
                ->each(fn ($model) => $model->delete())
        );
    }

    public function transactions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(WalletTransaction::class);
    }
}
