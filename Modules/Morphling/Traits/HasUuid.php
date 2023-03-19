<?php

namespace Modules\Morphling\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @mixin Model
 *
 * @property string $uuid
 */
trait HasUuid
{
    /**
     * The "booting" method of the model.
     */
    public static function bootHasUuid(): void
    {
        static::creating(function (self $model): void {
            // Automatically generate a UUID if using them, and not provided.
            if (empty($model->{$model->getUuidKeyName()})) {
                $model->{$model->getUuidKeyName()} = Str::uuid();
            }
        });
    }

    protected function getUuidKeyName(): string
    {
        return 'uuid';
    }
}
