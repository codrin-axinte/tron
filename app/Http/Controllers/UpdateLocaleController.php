<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Validation\Rule;

class UpdateLocaleController extends Controller
{
    public function __invoke(Request $request)
    {
        $availableLocales = array_keys(config('nova-translatable.locales', []));

        $data = $request->validate([
            'locale' => ['required', Rule::in($availableLocales)],
        ]);

        Redis::set('locale', $data['locale']);

        return new JsonResponse();
    }
}
