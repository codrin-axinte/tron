<?php

namespace App\Traits;

use App\Models\Team;

trait HasTeam
{
    protected static function bootHasTeam(): void
    {
        static::created(function ($model) {
            $model->ownedTeam()->create();
        });

        static::deleting(function ($model) {
            $model->ownedTeam->delete();
        });
    }

    public function ownedTeam(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Team::class);
    }

    public function memberOfTeams(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'team_member')->latest();
    }
}
