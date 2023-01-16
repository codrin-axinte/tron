<?php

namespace Modules\Morphling\Http\Controllers\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Morphling\Events\FrontendBootstrap;

class BootstrapController extends Controller
{
    public function __invoke(Request $request)
    {
        $data = collect([
            ...event(new FrontendBootstrap($request)),
        ])
            ->filter()
            ->toArray();

        return new JsonResponse($data);
    }
}
