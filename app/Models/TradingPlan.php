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

    public function remainingTime(): Attribute
    {
        return new Attribute(
            get: function () {
                $this->loadMissing(['pricingPlan', 'pricingPlan.planSettings']);

                $plan = $this->pricingPlan;
                $hours = $plan->planSettings->expiration_hours;
                $expires_at = $plan->created_at->addHours($hours);

                return $expires_at->toDateTimeString();
            }
        );
    }
}
