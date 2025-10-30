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

    // Detalhes de uma tarefa específica do usuário autenticado
    Route::get('/todo/{id}', [TodoController::class, 'selectTodo']);

    // Tarefas paginadas do usuário autenticado
    Route::get('/todos', [TodoController::class, 'paginatedTodo']);
    
    // Atualizar uma tarefa específica do usuário autenticado
    Route::put('todo/{id}', [TodoController::class, 'updateTodo']);

    // Route::delete('/todo/{id}', [TodoController::class, 'deleteTodo']);
    Route::delete('todo/{id}', [TodoController::class, 'deleteTodo']);
});