<?php

namespace App\Http\Controllers;

use App\Models\Todo;
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
    
        // Cria e persiste a tarefa no banco de dados
        $todo = new Todo();
        $todo->title = $request->input('title');
        $todo->description = $request->input('description');
        // Se houver usuário autenticado, associe (assumindo a coluna user_id na tabela)
        if ($request->user()) {
            $todo->user_id = $request->user()->id;
        }
        $todo->save();
    
        return response()->json([
            'message' => 'Todo created successfully',
            'todo' => $todo
        ], 201);
    }
    
    public function allTodos(Request $request)
    {
        $request->user(); // Obtém o usuário autenticado
        $allTodos = Todo::all(); // Exemplo de obtenção de todas as tarefas
        return response()->json([
            'todos' => $allTodos
        ]);    
    }

    public function selectTodo(Request $request, $id)
    {
        $request->user(); // Obtém o usuário autenticado
        $todo = null; // Aqui você buscaria a tarefa específica do banco de dados pelo ID
        return response()->json([
            'todo' => $todo
        ]);
    }

    public function paginatedTodo(Request $request)
    {
        $perPage = $request->get('limit', 10);
        
        $todos = Todo::orderBy('id')->paginate($perPage);
        
        return response()->json([
            'current_page' => $todos->currentPage(),
            'data' => $todos->items(),
            'total' => $todos->total(),
            'per_page' => $todos->perPage(),
            'last_page' => $todos->lastPage()
        ]);
    }


    public function updateTodo(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string',
            'description' => 'sometimes|nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors()
            ], 422);
        }
        
    }

    public function deleteTodo(Request $request, $id)
    {
        Todo::where('id', $id)->delete();
        return response()->json([
            'message' => 'Todo deleted successfully'
        ]);
    }

}
