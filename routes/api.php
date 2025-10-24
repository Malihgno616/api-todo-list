<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/welcome', [UserController::class, 'hello']);
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [UserController::class, 'logout']);
    Route::get('/profile', [UserController::class, 'profile']);
});