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
        $todo = new Todo();
        $todo = Todo::find($id); // Exemplo de obtenção de todas as tarefas
        return response()->json([
            'todo' => [
                $todo->id,
                $todo->title,
                $todo->description
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
        
        $todo = new Todo();
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
        
    }

    public function deleteTodo(Request $request, $id)
    {
        
        Todo::where('id', $id)->delete();
        $request->user(); // Obtém o usuário autenticado
        
        return response()->json([
            'message' => 'Todo deleted successfully'
        ]);
    }

}
