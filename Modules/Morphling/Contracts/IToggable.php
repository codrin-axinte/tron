<?php

namespace Modules\Morphling\Contracts;

interface IToggable
{
    public function toggle(): bool;

    public function enable(): bool;

    public function disable(): bool;
}
