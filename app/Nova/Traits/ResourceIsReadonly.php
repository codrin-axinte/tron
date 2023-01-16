<?php

namespace App\Nova\Traits;

use Illuminate\Http\Request;

trait ResourceIsReadonly
{
    public function authorizedToDelete(Request $request): bool
    {
        return false;
    }

    public static function authorizedToCreate(Request $request): bool
    {
        return false;
    }

    public function authorizedToUpdate(Request $request): bool
    {
        return false;
    }

    public function authorizedToReplicate(Request $request): bool
    {
        return false;
    }
}
