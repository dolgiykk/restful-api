<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\TwoFactorAuthController;
use App\Http\Controllers\UserAddressController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::post('/logout/other-devices', [AuthController::class, 'logoutOtherDevices'])->middleware('auth:sanctum');
    Route::delete('/logout/device/{id}', [AuthController::class, 'logoutDevice'])->middleware('auth:sanctum');
    Route::get('/tokens', [AuthController::class, 'tokens'])->middleware('auth:sanctum');

    Route::post('/2fa/enable', [TwoFactorAuthController::class, 'enable'])->middleware('auth:sanctum');
    Route::post('/2fa/verify', [TwoFactorAuthController::class, 'verify'])->middleware('auth:sanctum');

    Route::post('/password/forgot', [PasswordController::class, 'forgot']);
    Route::get('/password/reset-form', [PasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [PasswordController::class, 'reset']);
    Route::post('/password/change', [PasswordController::class, 'change'])->middleware('auth:sanctum');

    Route::apiResource('/users', UserController::class);
    Route::apiResource('/addresses', AddressController::class);

    Route::get('/users/{user}/addresses', [UserAddressController::class, 'getByUserId'])/*->middleware('auth:sanctum')*/;
    Route::post('/users/{user}/addresses', [UserAddressController::class, 'attach']);
    Route::post('/users/{user}/addresses/detach', [UserAddressController::class, 'detach']);
    Route::post('/users/{user}/addresses/detach-all', [UserAddressController::class, 'detachAll']);

    Route::post('/email/verify/send', [EmailVerificationController::class, 'send'])
        ->middleware('auth:sanctum')
        ->name('verification.send');
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
        ->name('verification.verify');
});
