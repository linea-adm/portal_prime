<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Sanctum;

class AuthController extends Controller
{
    public function loginProtheus(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];

        if (Auth::attempt($credentials)) {
            $token = Auth::user()->createToken('token-name')->plainTextToken;

            return response()->json(['token' => $token]);
        }

        throw ValidationException::withMessages([
            'codigo' => ['Credenciais inválidas'],
        ]);
    }
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
