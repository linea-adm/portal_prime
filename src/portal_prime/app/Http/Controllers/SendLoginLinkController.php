<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SendLoginLinkController extends Controller
{
    //
    public function send(Request $request) {

        try {
        // dados recebidos do ERP
        $email = $request->input('email');
        $codCliente = $request->input('cod_cliente');
        $codLoja = $request->input('cod_loja');
        $notas = $request->input('notas'); // Lista de notas recebidas do ERP

        // busca cliente na base
        $cliente = Cliente::where('codigo', $codCliente)
                          ->where('loja', $codLoja)
                          ->where('email', 'LIKE', "%$email%")
                          ->first();

        // gerar token e salvar
        $token = $this->gerarToken($cliente, $email);

        // montar URL de redirecionamento
        // $url = "/agendamento?token=$token";
        $url = "/agendamento?token=$token&notas=" . urlencode(json_encode($notas));


        // retornar dados de resposta
        return [
              'login_url' => $url
        ];

          } catch (\Exception $e) {

            // retornar erro 500
            return response()->json(['error' => 'Falha ao gerar link de login'], 500);

          }
      }

      private function gerarToken($cliente, $email){

        // payload do token
        $payload = [
           'sub' => $cliente->id,
           'email' => $email,
           'cnpj' => $cliente->cnpj,
           'codigo'=> $cliente->codigo,
           'loja' => $cliente->loja
        ];

        // gerar o token JWT
        $token = JWT::encode($payload, env('JWT_KEY'), 'HS256', null, ['max_length' => 500]);

        // salvar registro relacionado na tabela password_resets
        DB::table('password_resets')->insert([
           'email' => $email,
           'token' => $token
        ]);

        return $token;

      }
}
