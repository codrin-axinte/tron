<?php

namespace Modules\Morphling\Contracts;

/**
 * @method whereOwnedBy(string|int $id)
 * @method whereNullOrOwnedBy(string|int $id)
 */
interface CanBeOwned
{
    public function ownerColumnName(): string;

    public function isOwnedBy(CanOwnModels $owner);
}
