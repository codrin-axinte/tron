<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Modules\Wallet\Models\PricingPlan;

class TradingPlan extends Model
{
    protected $guarded = [];

    protected $casts = [
        'amount' => 'float',
        'start_amount' => 'float',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pricingPlan(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(PricingPlan::class);
    }

    public function scopeActive(Builder $query, int $hours = 1): Builder
    {
        return $query->where('created_at', '>=', now()->subHours($hours));
    }

    public function scopeExpired(Builder $query, int $hours = 1): Builder
    {
        return $query->where('created_at', '<', now()->subHours($hours));
    }
}
