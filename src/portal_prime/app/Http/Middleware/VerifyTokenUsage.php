<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\PasswordReset;

class VerifyTokenUsage
{
    public function handle($request, Closure $next)
    {
        try {
            $token = $request->query('token');

            if (!$token) {
                // Se não houver token na query string, trate conforme necessário
                return response()->json(['error' => 'Token não fornecido'], 401);
            }

            // Verificar se o token já existe no banco
            $passwordReset = PasswordReset::where('token', $token)->first();

            if (!$passwordReset) {
                // Token inválido, erro de autenticação
                return response()->json(['error' => 'Token inválido'], 401);
            }

            if ($passwordReset->used) {
                // Token já foi utilizado, erro de autenticação
                return response()->json(['error' => 'Token já foi utilizado'], 401);
            }

            // Adicione verificação adicional, se necessário

            return $next($request);
        } catch (\Exception $e) {
            // Tratar exceções, se necessário
            return response()->json(['error' => 'Erro no middleware VerifyTokenUsage'], 500);
        }
    }
}
