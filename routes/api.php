<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/users', [UserController::class, 'getUsers']);
    Route::get('/users/{user}', [UserController::class, 'getUser']);
    Route::middleware('auth:sanctum')->get('/profile', [AuthController::class, 'profile']);
});
