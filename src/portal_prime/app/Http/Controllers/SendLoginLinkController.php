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
        $email_usuario = $request->input('email_usuario');
        $codCliente = $request->input('cod_cliente');
        $codLoja = $request->input('cod_loja');
        $notas = $request->input('notas'); // Lista de notas recebidas do ERP

        $notas_encoded = urlencode(json_encode($notas));
        // busca cliente na base
        $cliente = Cliente::where('codigo', $codCliente)
                          ->where('loja', $codLoja)
                          // ->where('email', 'LIKE', "%$email%")
                          ->first();
          // dd($email);
        // gerar token e salvar
        $token = $this->gerarToken($cliente, $email);

        // montar URL de redirecionamento
        // $url = "/agendamento?token=$token";
        // $url = "/agendamento?token=".$token.'&email_usuario='.$email_usuario."&notas=" . urlencode(json_encode($notas));


        // Montar URL de redirecionamento
        $url = "/agendamento?token=" . urlencode($token) . '&email_usuario=' . urlencode($email_usuario) . "&aNotas=" .$notas_encoded;


        // Retornar dados de resposta
        $response = [
          'login_url' => $url
      ];

      return response()->json($response, 200, [], JSON_UNESCAPED_SLASHES);

          } catch (\Exception $e) {
            return $e->getMessage();
            // retornar erro 500
            return response()->json(['error' => 'Falha ao gerar link de login','message' => $e->getMessage()], 500);

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
