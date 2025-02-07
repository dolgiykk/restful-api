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
    Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
    Route::middleware('auth:sanctum')->post('/close-other-sessions', [AuthController::class, 'closeOtherSessions']);
    Route::middleware('auth:sanctum')->post('/change-password', [AuthController::class, 'changePassword']);
    Route::middleware('auth:sanctum')->post('/enable2FA', [AuthController::class, 'enable2FA']);
    Route::middleware('auth:sanctum')->post('/verify2FA', [AuthController::class, 'verify2FA']);
    Route::middleware('auth:sanctum')->get('/profile', [AuthController::class, 'profile']);
});
