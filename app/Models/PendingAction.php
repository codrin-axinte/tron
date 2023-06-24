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

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function mergeMeta(array $meta): static
    {
        $this->meta = array_merge($this->meta, $meta);

        return $this;
    }
}
