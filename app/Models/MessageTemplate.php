<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class MessageTemplate extends Model
{
    use HasFactory, HasTranslations;

    public array $translatable = ['content'];
    protected $casts = [
        'hooks' => 'array',
    ];

    public function scopeWhereInHooks($query, array $hooks)
    {
        return $query->whereJsonContains('hooks', $hooks);
    }
}
