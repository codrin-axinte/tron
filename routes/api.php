<?php

use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

if (app()->environment('local')) {
    Route::post('/tokens/create', function (Request $request) {
        $user = User::findOrFail($request->get('user_id', 1));
        $token = $user->createToken($request->token_name);

        return ['token' => $token->plainTextToken];
    });
}

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/user', [UserController::class, 'show']);
    Route::delete('/user', [UserController::class, 'destroy'])->middleware(['password.confirm']);
    Route::put('/user/update-password', [\App\Http\Controllers\UserController::class, 'updatePassword']);

    //region Notifications
    Route::get('/notifications/{group}', [\App\Http\Controllers\NotificationsController::class, 'index']);
    Route::put('/notifications/{group}/{notification}/read', [\App\Http\Controllers\NotificationsController::class, 'markAsRead']);
    Route::put('/notifications/{group}/read', [\App\Http\Controllers\NotificationsController::class, 'markAllAsRead']);
    Route::get('/notifications/{group}/count', [\App\Http\Controllers\NotificationsController::class, 'count']);
    //endregion
});

Route::put('/language', \App\Http\Controllers\UpdateLocaleController::class);
Route::get('/welcome', \App\Http\Controllers\LandingController::class);
Route::get('/test', function () {

    $request = \App\Http\Integrations\Tron\Requests\GenerateRandomWalletRequest::make();

    $response = $request->send();

    return new \Illuminate\Http\JsonResponse($response->json());
});
