<?php

namespace Modules\Morphling\Contracts;

use Modules\Morphling\Events\FrontendBootstrap;

interface Bootstrapper
{
    public function handle(FrontendBootstrap $event): mixed;
}
