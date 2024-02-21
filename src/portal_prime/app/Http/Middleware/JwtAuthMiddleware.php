<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Firebase\JWT\Key;
use LdapRecord\Models\ActiveDirectory\User;


class JwtAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Extrair o token da requisição
        $authHeader = $request->header('Authorization');
        $token = str_replace('Bearer ', '', $authHeader);

        // Decodificar o token JWT
        $key = env('JWT_SECRET'); // Chave secreta
        try {
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            // Validar o usuário
            // $user = User::find('cn='.env().',ou=ANAPOLIS,dc=eicbrasil,dc=local');
            // return response()->json();
            $query = User::on('default');
            $user = $query->findBy('samaccountname', $decoded->sub);
                // return response()->json($decoded->sub);
            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }

            // Autenticar o usuário para esta requisição
            // Auth::login($user);

            // Chamar o próximo middleware/rota
            return $next($request);
        } catch (\Exception $e) {
            // O token JWT é inválido
            return response()->json(['error' => 'Invalid token'], 401);
        }
    }
}
