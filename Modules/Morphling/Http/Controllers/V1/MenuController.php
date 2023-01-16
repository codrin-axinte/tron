<?php

namespace Modules\Morphling\Http\Controllers\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        return new JsonResponse(nova_get_menus($request));
    }

    public function show(string $slug, ?string $locale = null)
    {
        return new JsonResponse(nova_get_menu_by_slug($slug, $locale));
    }
}
