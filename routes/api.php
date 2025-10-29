<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;

Route::get('/welcome', [UserController::class, 'hello']);
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [UserController::class, 'profile']);
    Route::post('/logout', [UserController::class, 'logout']);
    Route::post('/todo', [TodoController::class, 'createTodo']);
    // Route::get('/todos', [TodoController::class, 'getAllTodos']);
    // Route::get('/todos', [TodoController::class, 'paginatedTodo']);
    // Route::get('/todo/{id}', [TodoController::class, 'selectTodo']);
    // Route::delete('/todo/{id}', [TodoController::class, 'deleteTodo']);
});