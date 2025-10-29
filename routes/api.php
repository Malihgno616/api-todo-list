<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;

Route::get('/welcome', [UserController::class, 'hello']);
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

// Rotas protegidas por autenticação
Route::middleware('auth:sanctum')->group(function () {
    // Detalhes do usuário autenticado
    Route::get('/profile', [UserController::class, 'profile']);

    // Logout do usuário autenticado
    Route::post('/logout', [UserController::class, 'logout']);
    
    // Criar nova tarefa apenas o usuário autenticado
    Route::post('/todo', [TodoController::class, 'createTodo']);

    // Todas as tarefas do usuário autenticado
    Route::get('/todos', [TodoController::class, 'allTodos']);

    // Route::get('/todo/{id}', [TodoController::class, 'selectTodo']);
    
    // Route::get('/todos', [TodoController::class, 'paginatedTodo']);
    // Route::delete('/todo/{id}', [TodoController::class, 'deleteTodo']);
});