<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/users', [UserController::class, 'getUsers']);
    Route::get('/users/{user}', [UserController::class, 'getUser']);
    Route::post('/forgot-password', [AuthController::class, 'sendResetToken']);
    Route::get('/reset-password', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::post('/close-other-sessions', [AuthController::class, 'closeOtherSessions'])->middleware('auth:sanctum');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->middleware('auth:sanctum');
    Route::post('/enable2FA', [AuthController::class, 'enable2FA'])->middleware('auth:sanctum');
    Route::post('/verify2FA', [AuthController::class, 'verify2FA'])->middleware('auth:sanctum');
    Route::get('/profile', [AuthController::class, 'profile'])->middleware('auth:sanctum');
    Route::post('/email/send-verify', [AuthController::class, 'sendVerificationEmail'])
        ->name('verification.send');
    Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
        ->name('verification.verify');
});
