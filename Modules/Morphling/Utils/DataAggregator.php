<?php

namespace Modules\Morphling\Utils;

use Illuminate\Support\Collection;

class DataAggregator
{
    public static function event(string|object $event, array $mergeAfter = [], array $mergeBefore = []): Collection
    {
        return collect([
            ...$mergeBefore,
            ...event($event),
            ...$mergeAfter,
        ])
            ->filter() // Filter out empty values
            ->flatten();
    }
}
