<?php

use Modules\Morphling\Http\Controllers\V1\BootstrapController;

Route::group(['prefix' => 'v1'], function () {
    Route::get('/bootstrap', BootstrapController::class);
    Route::get('/menus', [\Modules\Morphling\Http\Controllers\V1\MenuController::class, 'index']);
    Route::get('/menus/{slug}/{locale?}', [\Modules\Morphling\Http\Controllers\V1\MenuController::class, 'show']);
});
