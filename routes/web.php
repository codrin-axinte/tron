<?php

Route::redirect('/', config('nova.path'))->name('login');

Route::middleware(['auth', 'can:viewSwagger'])->group(function () {
    Route::get('swagger-specs', \App\Http\Controllers\SwaggerController::class);
    Route::view('swagger', 'swagger-ui')->name('swagger.index');

    Route::get('/wallet', \App\Http\Controllers\TronWalletController::class);
});


Route::get('/compound', function (\Illuminate\Http\Request $request) {
    $calculator = app(\App\Services\CompoundInterestCalculator::class);

    $principal = $request->get('principal', 100);
    $rate = $request->get('rate', 0.01);
    $time = $request->get('time', 1);
    $days = $request->get('days', 30);

    return view('compound-simulation', ['data' => $calculator->simulate($principal, $rate, $time, $days)]);
})->name('compound-simulation');

Route::get('/test', function () {

    $request = \App\Http\Integrations\Tron\Requests\TRC20\GetAccountBalanceRequest::make();
    $request->addData('address', 'TRGr2qUpJAuA4JtQVzd1CYhJVogwWpXfq6');

    $data = $request->send()->json();

    dd($data);

});
