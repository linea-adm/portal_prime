<?php

namespace App\Http\Controllers\Auth;
// app\Http\Controllers\Auth\JwtClienteController.php

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JwtClienteController extends Controller
{
    public function generateToken(Request $request)
    {
        $credentials = $request->only('cnpj', 'password');

        if (!Auth::guard('cliente')->attempt($credentials)) {
            return response()->json(['error' => 'CNPJ ou senha incorretos.'], 401);
        }

        $cliente = Cliente::where('cnpj', $request->input('cnpj'))->first();

        $tokenData = [
            'cnpj' => $cliente->cnpj,
            'codigo_cliente' => $cliente->codigo_cliente,
            'codigo_loja' => $cliente->codigo_loja,
            'email' => $cliente->email,
        ];

        $token = $this->generateJwtToken($tokenData);

        return response()->json(compact('token'));
    }

    private function generateJwtToken($data)
    {
        // Implemente a lógica para gerar manualmente o token JWT aqui
        // Dica: Use base64_encode, hash_hmac, etc., conforme necessário
        // Certifique-se de incluir a informação adequada no token

        // Exemplo Simples:
        $header = base64_encode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
        $payload = base64_encode(json_encode($data));
        $signature = hash_hmac('sha256', "$header.$payload", 'secreto');

        return "$header.$payload.$signature";
    }
}
