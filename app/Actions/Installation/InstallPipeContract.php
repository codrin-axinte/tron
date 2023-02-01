<?php

namespace App\Actions\Installation;

interface InstallPipeContract
{
    public function handle($payload, \Closure $next);
}
