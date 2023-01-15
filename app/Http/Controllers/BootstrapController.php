<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class BootstrapController extends Controller
{
    public function __invoke(): JsonResponse
    {
        // Bootstrap all the data needed for the app, such as: settings, menus pages, permissions, routes, etc.

        return new JsonResponse([

        ]);
    }
}
