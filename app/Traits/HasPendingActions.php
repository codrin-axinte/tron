<?php

namespace App\Traits;

use App\Models\PendingAction;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasPendingActions
{
    public function pendingActions(): HasMany
    {
        return $this->hasMany(PendingAction::class);
    }
}
