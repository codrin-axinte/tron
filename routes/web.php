<?php

Route::redirect('/', config('nova.path'))->name('login');

Route::middleware(['auth', 'can:viewSwagger'])->group(function () {
    Route::get('swagger-specs', \App\Http\Controllers\SwaggerController::class);
    Route::view('swagger', 'swagger-ui')->name('swagger.index');
});
