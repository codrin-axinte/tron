<?php

namespace Modules\Morphling\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * @mixin Model
 *
 * @method enabled(): static
 * @method disabled(): static
 */
trait HasToggle
{
    protected function toggleColumn(): string
    {
        return 'enabled';
    }

    public function toggle(): bool
    {
        $column = $this->toggleColumn();

        return $this->update([$column => ! $this->{$column}]);
    }

    public function enable(): bool
    {
        return $this->update([$this->toggleColumn() => true]);
    }

    public function disable(): bool
    {
        return $this->update([$this->toggleColumn() => false]);
    }

    public function scopeEnabled(Builder $query): Builder
    {
        return $query->where($this->toggleColumn(), true);
    }

    public function scopeDisabled(Builder $query): Builder
    {
        return $query->where($this->toggleColumn(), false);
    }
}
