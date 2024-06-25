<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmailClienteLoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:email_clientes')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.email_clientes.login');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::guard('email_clientes')->attempt([
            'email' => $request->email,
            'password' => $request->password,
        ])) {
            // return redirect()->intended(route('programarEntregas'));
            return redirect()->intended(route('programarEntregasAbertas'));
        }

        return redirect()->back()->withInput($request->only('email', 'remember'))->withErrors(['email' => 'Falha no login, tente novamente.']);
    }

    public function logout()
    {
        Auth::guard('email_clientes')->logout();
        return redirect()->route('email_clientes.login');
    }
}
