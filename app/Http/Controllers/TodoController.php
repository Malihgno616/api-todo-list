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
        $todo->completed = false;
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
        $todo = new Todo();
        $todo = Todo::find($id);
        
        if(!$todo) {
            return response()->json([
                'error' => 'Todo not found or already deleted'
            ], 404);
        }

        // Exemplo de obtenção de todas as tarefas
        return response()->json([
            'todo' => [
                'id' => $todo->id,
                'title' => $todo->title,
                'description' => $todo->description,
                'completed' => $todo->completed
            ]
        ]);
    }

    public function paginatedTodo(Request $request)
    {
        $page = $request->get('page', 1);
        $perPage = $request->get('limit', 10);

        $todos = Todo::orderBy('id')
            ->paginate($perPage, ['*'], 'page', $page);
            
        return response()->json([
            'current_page' => $todos->currentPage(),
            'data' => $todos->items(),
            'total' => $todos->total(),
        ]);
    }

    public function updateTodo(Request $request, $id)
    {
        $todo = Todo::find($id);

        if (!$todo) {
            return response()->json([
                'error' => 'Todo not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string',
            'description' => 'sometimes|nullable|string',
            'completed' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors()
            ], 422);
        }

        if ($request->has('title')) {
            $todo->title = $request->input('title');
        }
        if ($request->has('description')) {
            $todo->description = $request->input('description');
        }
        if ($request->has('completed')) {
            $todo->completed = (bool) $request->input('completed');
        }

        $todo->save();

        return response()->json([
            'message' => 'Todo updated successfully',
            'todo' => [
                'id' => $todo->id,
                'title' => $todo->title,
                'description' => $todo->description,
                'completed' => $todo->completed
            ]
        ]);
    }

    public function deleteTodo(Request $request, $id)
    {   
        $todo = Todo::find($id);
        $request->user(); // Obtém o usuário autenticado
        if (!$todo) {
            return response()->json([
                'error' => 'Todo not found or already deleted'
            ], 404);
        }

        $todo->delete();

        return response()->json([
            'message' => 'Todo deleted successfully'
        ]);
    }

}
