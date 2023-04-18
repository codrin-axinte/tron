<?php

namespace App\Traits;

use App\Models\PricingPlanSettings;

trait HasPricingPlanSettings
{
    protected static function bootHasPricingPlanSettings(): void
    {
        static::created(fn ($model) => $model->planSettings()->create());

        static::deleting(fn ($model) => $model->planSettings->delete());
    }

    public function planSettings(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(PricingPlanSettings::class);
    }
}
