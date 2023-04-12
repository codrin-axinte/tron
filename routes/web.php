<?php

Route::redirect('/', config('nova.path'))->name('login');

Route::middleware(['auth', 'can:viewSwagger'])->group(function () {
    Route::get('swagger-specs', \App\Http\Controllers\SwaggerController::class);
    Route::view('swagger', 'swagger-ui')->name('swagger.index');

    Route::get('/wallet', \App\Http\Controllers\TronWalletController::class);
});


Route::get('/compound', function (\Illuminate\Http\Request $request) {
    $calculator = app(\App\Services\CompoundInterestCalculator::class);

    $principal = $request->get('principal', 1000);
    $rate = $request->get('rate', 0.01);
    $time = $request->get('time', 1);
    $days = $request->get('days', 30);

    $data = $calculator->simulate($principal, $rate, $time, $days);

    return view('compound-simulation', ['data' => $data]);
})->name('compound-simulation');

Route::get('/compound-hour', function (\Illuminate\Http\Request $request) {
    $calculator = app(\App\Services\CompoundInterestCalculator::class);

    $principal = $request->get('principal', 1000);
    $rate = $request->get('rate', 0.00125);

    $data = $calculator->simulateHourly($principal, $rate, 24);
    dd($data);
    return response()->json($data);
})->name('compound-simulation');

Route::get('/test', function () {

    return [];

});
