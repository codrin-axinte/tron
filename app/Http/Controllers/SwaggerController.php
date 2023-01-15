<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\File;

class SwaggerController extends Controller
{
    /**
     * @throws FileNotFoundException
     */
    public function __invoke()
    {
        $filePath = storage_path('app/specs/specs.json');

        return File::get($filePath);
    }
}
