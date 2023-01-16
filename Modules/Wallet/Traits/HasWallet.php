<?php

namespace Modules\Wallet\Traits;

use Illuminate\Database\Eloquent\Model;
use Modules\Wallet\Models\Wallet;

/**
 * @mixin Model
 *
 * @property-read Wallet $wallet
 */
trait HasWallet
{
    protected static function bootHasWallet(): void
    {
        static::created(fn ($model) => $model->wallet()->create());

        static::deleting(fn ($model) => $model->wallet?->delete());
    }

    public function wallet(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Wallet::class);
    }
}
