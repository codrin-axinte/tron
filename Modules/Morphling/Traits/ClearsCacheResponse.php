<?php

namespace Modules\Morphling\Traits;

use Spatie\ResponseCache\Facades\ResponseCache;

trait ClearsCacheResponse
{
    public static function bootClearsResponseCache(): void
    {
        self::created(function () {
            ResponseCache::clear();
        });

        self::updated(function () {
            ResponseCache::clear();
        });

        self::deleted(function () {
            ResponseCache::clear();
        });
    }
}
