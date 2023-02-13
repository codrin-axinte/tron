<?php

namespace App\Actions\Installation;

use App\Services\PoolManager;

class CreateRandomPools implements InstallPipeContract
{

    public function __construct(protected PoolManager $poolManager)
    {
    }

    public function handle($payload, \Closure $next)
    {
        $numberOfPools = config('tron.pools', 5);
        $this->poolManager->createRandom(isCentral: true);
        $this->poolManager->createRandom($numberOfPools - 1);
    }
}
