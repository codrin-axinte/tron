<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Wallet\Models\PricingPlan;

class PricingPlanSettings extends Model
{
    protected $casts = [
        'meta' => 'array',
        'commissions' => 'array',
    ];

    protected $guarded = [];

    public function pricingPlan(): BelongsTo
    {
        return $this->belongsTo(PricingPlan::class);
    }

    public function commissionsByDepth(): Attribute
    {
        return new Attribute(get: fn () => collect($this->commissions)->flatten());
    }
}
