<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{   

    public function hello()
    {
        return response()->json([
            "message" => "Welcome to user API!!"
        ]);
    }

    public function register(Request $request)
    {
        // Validação básica
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors()
            ], 422);
        }

        try {
            // Criar usuário
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            
            // Gerar token
            $token = $user->createToken('auth-token')->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Registration failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'error' => 'Invalid credentials'
            ], 401);
        }

        $token = $user->createToken('auth-token')->plainTextToken;
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);

    }

    /**
 * Logout do usuário (PROTEGIDO - requer token)
 */
    public function logout(Request $request)
    {
        try {
            Log::info('Tentativa de logout', ['user_id' => $request->user()->id]);
            
            // Delete o token atual do usuário
            $request->user()->currentAccessToken()->delete();

            Log::info('Logout realizado com sucesso');

            return response()->json([
                'success' => true,
                'message' => 'Logout realizado com sucesso'
            ], 200);

        } catch (\Exception $e) {
            Log::error('Erro no logout: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Falha no logout',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function profile(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'user' => [
                    'id' => $request->user()->id,
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                    'created_at' => $request->user()->created_at,
                ]
            ]
        ], 200);
    }

}
