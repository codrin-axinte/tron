<?php

namespace App\Traits;

use App\Models\ReferralLink;

trait HasReferralLinks
{
    protected static function bootHasReferralLinks(): void
    {
        static::created(function ($model) {
            $model->referralLinks()->create([
                'code' => \Str::random(6),
            ]);
        });

        static::deleting(function ($model) {
            $model->referralLinks()->cursor()->each(fn ($link) => $link->delete());
        });
    }

    public function referralLink(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(ReferralLink::class)->latestOfMany();
    }

    public function referralLinks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ReferralLink::class);
    }
}
