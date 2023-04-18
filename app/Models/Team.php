<?php

namespace App\Models;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Model;

class Team extends Model implements Renderable
{
    public function owner(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function members(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'team_member')->withTimestamps();
    }

    public function render(): string
    {
        $members = $this->members;
        $message = \Str::of("**Your team score is: $this->score ({$members->count()} members)**")->newLine(2);

        foreach ($members as $member) {
            //$plan = $member->pricingPlans->first()->name ?? 'No plan';
            $message->append('- '.$member->name)->newLine();
        }

        return $message->toString();
    }
}
