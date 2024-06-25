<?php
// No arquivo app/Http/Controllers/EmailClienteController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmailCliente;
use App\Models\Cliente;
use Illuminate\Support\Facades\Hash;

class EmailClienteController extends Controller
{
    public function create(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:emails_clientes,email',
            'codigo_cliente' => 'required|exists:clientes,codigo',
            'loja' => 'required'
        ]);

        $cliente = Cliente::where('codigo', $validated['codigo_cliente'])
                          ->where('loja', $validated['loja'])
                          ->firstOrFail();

        $emailCliente = new EmailCliente();
        $emailCliente->cliente_id = $cliente->id;
        $emailCliente->email = $validated['email'];
        $emailCliente->password = Hash::make('defaultpassword'); // Gere uma senha inicial ou solicite ao cliente que defina uma.

        $emailCliente->save();

        return response()->json(['message' => 'Email cadastrado com sucesso.'], 201);
    }
}
