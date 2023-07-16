<?php

use App\Http\Controllers\SwaggerController;
use App\Http\Controllers\TronWalletController;
use App\Services\CompoundInterestCalculator;
use Illuminate\Http\Request;

Route::redirect('/', config('nova.path'))->name('login');

Route::middleware(['auth', 'can:viewSwagger'])->group(function () {
    Route::get('swagger-specs', SwaggerController::class);
    Route::view('swagger', 'swagger-ui')->name('swagger.index');

    Route::get('/wallet', TronWalletController::class);
});

if(app()->isLocal()) {
    Route::get('/simulate-compound', function (Request $request, CompoundInterestCalculator $calculator) {

        $principal = $request->get('principal', 1000);
        $rate = $request->get('rate', 0.00125);

        $data = $calculator->simulate($principal, $rate, 24);

        return response()->json($data);
    })->name('compound-simulation');
}

