<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class LandingController extends Controller
{
    public function __invoke(): JsonResponse
    {
        // Load all the necessary data for the landing page.

        return new JsonResponse([

        ]);
    }
}
