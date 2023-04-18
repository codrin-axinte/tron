<?php

namespace App\Models;

use App\Enums\PendingActionType;
use Illuminate\Database\Eloquent\Model;

class PendingAction extends Model
{
    protected $guarded = [];

    protected $casts = [
        'meta' => 'json',
        'type' => PendingActionType::class,
    ];

    public function scopeAwaitsConfirmation($query)
    {
        return $query->where('type', PendingActionType::AwaitingTransactionConfirmation);
    }
}
