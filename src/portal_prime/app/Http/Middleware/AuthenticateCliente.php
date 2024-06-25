<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticateCliente
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('email_clientes')->check()) {
            return redirect()->route('email_clientes.login');
        }

        return $next($request);
    }
}
