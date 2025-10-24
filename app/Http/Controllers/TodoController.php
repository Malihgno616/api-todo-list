<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TodoController extends Controller
{
    public function createTodo(Request $request)
    {
        // Validação básica
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'description' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors()
            ], 422);
        }

        // Lógica para criar uma nova tarefa (todo) aqui

        return response()->json([
            'message' => 'Todo created successfully'
        ], 201);
    }

    public function updateTodo()
    {
        
    }

    public function deleteTodo()
    {
        
    }

}
