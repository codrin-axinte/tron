<?php

namespace Modules\Morphling\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Morphling\Utils\Table;

class Module extends Model
{
    protected $guarded = [];

    public function getTable(): string
    {
        return Table::modules();
    }

    protected $casts = [
        'enabled' => 'boolean',
        'requirements' => 'json',
        'keywords' => 'json',
        'meta' => 'json',
        'priority' => 'int',
    ];

    public function scopeEnabled($query)
    {
        return $query->whereEnabled(true);
    }

    public function scopeDisabled($query)
    {
        return $query->whereEnabled(false);
    }
}
