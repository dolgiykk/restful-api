<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PersonalAccessTokenController;
use App\Http\Controllers\Auth\TwoFactorAuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

    Route::post('/enable2FA', [TwoFactorAuthController::class, 'enable2FA'])->middleware('auth:sanctum');
    Route::post('/verify2FA', [TwoFactorAuthController::class, 'verify2FA'])->middleware('auth:sanctum');

    Route::post('/forgot-password', [PasswordController::class, 'sendResetToken']);
    Route::get('/reset-password', [PasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordController::class, 'reset']);
    Route::post('/change-password', [PasswordController::class, 'change'])->middleware('auth:sanctum');

    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{user}', [UserController::class, 'getUser']);

    Route::post('/email/verify/send', [EmailVerificationController::class, 'send'])
        ->name('verification.send');
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
        ->name('verification.verify');

    Route::post('/tokens', [PersonalAccessTokenController::class, 'index'])->middleware('auth:sanctum');
    Route::post('/logout-other-devices', [PersonalAccessTokenController::class, 'revokeOtherTokens'])->middleware('auth:sanctum');
});
