<?php

use Modules\Wallet\Http\Controllers\PricingPlansController;
use Modules\Wallet\Http\Controllers\WalletTransactionsController;

Route::group(['prefix' => 'v1'], function () {
    Orion::resource('pricing-plans', PricingPlansController::class)
        ->only(['index', 'show', 'search']);

    Orion::resource('transactions', WalletTransactionsController::class)
        ->only(['index', 'search']);
});
