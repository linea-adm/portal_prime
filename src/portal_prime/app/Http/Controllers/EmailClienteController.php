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
    
        // Extrair partes necessárias do email, código do cliente e loja
        $loja = $validated['loja'];
        $email = $validated['email'];
        $codigoCliente = $validated['codigo_cliente'];
    
        // Extrair o usuário do email (parte antes do arroba)
        $usuarioEmail = explode('@', $email)[0];
    
        // Extrair os 2 últimos dígitos do código do cliente
        $ultimosDoisDigitosCodigoCliente = substr($codigoCliente, -2);
    
        // Construir a senha padrão
        $senhaPadrao = $loja . $usuarioEmail . $ultimosDoisDigitosCodigoCliente;
    
        $emailCliente = new EmailCliente();
        $emailCliente->cliente_id = $cliente->id;
        $emailCliente->email = $validated['email'];
        $emailCliente->password = Hash::make($senhaPadrao); 
    
        $emailCliente->save();
    
        return response()->json(['message' => 'Email cadastrado com sucesso.'], 201);
    }
    
}
