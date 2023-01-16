<?php

namespace App\Traits;

use App\Models\Team;

trait HasTeam
{
    protected static function bootHasTeam(): void
    {
        static::created(function ($model) {
            $model->team()->create();
        });

        static::deleting(function ($model) {
            $model->team->delete();
        });
    }

    public function team(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Team::class);
    }
}
