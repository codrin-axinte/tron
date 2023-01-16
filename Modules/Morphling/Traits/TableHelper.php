<?php

namespace Modules\Morphling\Traits;

use Illuminate\Support\Str;

/**
 * @property string $configPath
 */
trait TableHelper
{
    public static function __callStatic(string $name, array $arguments = [])
    {
        $table = static::prefix().Str::snake($name);
        if (empty($arguments)) {
            return $table;
        }

        return $table.'.'.collect($arguments)->join('.');
    }

    protected static function prefix(): string
    {
        return config(static::$configPath, '');
    }
}
