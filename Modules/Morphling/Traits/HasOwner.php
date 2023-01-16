<?php

namespace Modules\Morphling\Traits;

use Modules\Morphling\Contracts\CanOwnModels;

/**
 * @method owned()
 */
trait HasOwner
{
    public function ownerColumnName(): string
    {
        return 'user_id';
    }

    public function ownerId()
    {
        return $this[$this->ownerColumnName()];
    }

    public function isOwnedBy(?CanOwnModels $owner): bool
    {
        if (! $owner) {
            return false;
        }

        return $owner->getKey() === $this->ownerId();
    }

    public function scopeOwned($query): \Modules\Wizards\Models\Wizard|\Modules\Blog\Models\Post|\Illuminate\Database\Eloquent\Builder|\Modules\PageBuilder\Models\Content|\Modules\Listings\Models\Lead|\App\Models\UserProfile
    {
        return $query->whereOwnedBy(auth()->id());
    }

    public function scopeWhereOwnedBy($query, $ownerId)
    {
        return $query
            ->where($this->ownerColumnName(), $ownerId)
            ->whereNotNull($this->ownerColumnName());
    }

    public function scopeWhereNullOrOwnedBy($query, $ownerId)
    {
        return $query
            ->where($this->ownerColumnName(), $ownerId)
            ->orWhereNull($this->ownerColumnName());
    }
}
