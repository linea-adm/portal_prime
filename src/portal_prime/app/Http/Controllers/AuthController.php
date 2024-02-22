<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Sanctum;

class AuthController extends Controller
{

    public function loginProtheus(Request $request)
    {
        // Decodifica o JSON recebido na requisição
        $requestData = $request->json()->all();

        // Valida os campos obrigatórios
        $validator = Validator::make($requestData, [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Obtém as credenciais do JSON decodificado
        $credentials = [
            'email' => $requestData['email'],
            'password' => $requestData['password'],
        ];

        // Tenta autenticar o usuário
        if (Auth::attempt($credentials)) {
            // Cria um token para o usuário autenticado
            $token = Auth::user()->createToken('token-name')->plainTextToken;

            // Retorna o token como resposta JSON
            return response()->json(['token' => $token]);
        }

        // Se as credenciais estiverem incorretas, retorna um erro de validação
        return response()->json(['error' => 'Credenciais inválidas'], 401);
    }


//     public function loginProtheus(Request $request)
//     {
//         $request->validate([
//             'email' => 'required',
//             'password' => 'required',
//         ]);

//         $credentials = [
//             'email' => $request->input('email'),
//             'password' => $request->input('password'),
//         ];

//         if (Auth::attempt($credentials)) {
//             $token = Auth::user()->createToken('token-name')->plainTextToken;

//             return response()->json(['token' => $token]);
//         }

//         throw ValidationException::withMessages([
//             'codigo' => ['Credenciais inválidas'],
//         ]);
//     }
    public function login(Request $request)
    {
        $request->validate([
            'codigo' => 'required',
            'loja' => 'required',
            'cnpj' => 'required',
        ]);

        $credentials = [
            'codigo' => $request->input('codigo'),
            'loja' => $request->input('loja'),
            'cnpj' => $request->input('cnpj'),
        ];

        if (Auth::attempt($credentials)) {
            $token = Auth::user()->createToken('token-name')->plainTextToken;

            return response()->json(['token' => $token]);
        }

        throw ValidationException::withMessages([
            'codigo' => ['Credenciais inválidas'],
        ]);
    }
}
