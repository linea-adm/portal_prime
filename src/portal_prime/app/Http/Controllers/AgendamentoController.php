<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ApiHelper;
use App\Models\Cliente;

use App\Models\PasswordReset;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class AgendamentoController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Obter o token JWT da query string
            $token = $request->query('token');

            // // Validar se o token existe no banco
             $passwordReset = PasswordReset::where('token', $token)->first();

            if (!$passwordReset) {
                // Token inválido, erro de autenticação
                return response()->json(['error' => 'Token inválido'], 401);
            }

            // Decodificar o token JWT
            $decodedToken = JWT::decode($token, new Key(env('JWT_KEY'), 'HS256'));
            // $cliente = Cliente::where('id',$decodedToken->sub)->first();
            // Fazer login do cliente (sessão) com dados decodificados do JWT
            // Auth::guard('cliente')->login($cliente);
            Auth::guard('cliente')->loginUsingId(trim($decodedToken->sub));
            // $usuario=Auth::guard('cliente')->user();
            // Recuperar dados do token
            $cnpj = $decodedToken->cnpj;
            $email = $decodedToken->email;
            $codigoCliente = $decodedToken->codigo;
            $loja = $decodedToken->loja;

            // Excluir o registro do token do banco
            // $passwordReset = PasswordReset::where('email', $passwordReset->email)
            // ->where('token', $passwordReset->token)
            // ->delete();

            // Redirecionar para a página de agendamento com os dados do token
            return redirect('/programar-entregas')->with([
                'cnpj' => $cnpj,
                'email' => $email,
                'codigoCliente' => $codigoCliente,
                'loja' => $loja,
            ]);

        } catch (\Exception $e) {

            return response()->json(['error' => 'Erro no processo de login'], 500);
        }
    }

    public function programarEntregas()
    {
        // Recuperar dados passados do método index
        $cnpj = session('cnpj');
        $email = session('email');
        $codigoCliente = session('codigoCliente');
        $loja = session('loja');

        // $cliente = Cliente::where('cnpj', $cnpj)->where('codigo', $codigoCliente)->where('loja', $loja)->first();
        $cliente = Cliente::where('cnpj', $cnpj)->where('codigo', '000003')->where('loja', '03')->first();
        if(!$cliente) $cliente=Cliente::where('id',1)->first();
        // Dados do cliente
        $dadosCliente = [
            'nomeFantasia' => $cliente->fantasia,
            'municipioEstado' => $cliente->municipio . '-' . $cliente->uf,
            'cnpj' => $cliente->cnpj,
            'codigoCliente' => $codigoCliente,
            'loja' => $loja,
            'email' => $email, // Usando o e-mail da sessão, pois não está claro de onde ele vem
        ];

        $dadosNotasFiscais = ApiHelper::buscarNotasNaoAgendadas( '000003', '04');
        // $dadosNotasFiscais = ApiHelper::buscarNotasNaoAgendadas($cliente->codigo, $cliente->loja);

        // dd($dadosNotasFiscais);
        return view('agendamento', compact('dadosCliente','dadosNotasFiscais'));
    }

}
