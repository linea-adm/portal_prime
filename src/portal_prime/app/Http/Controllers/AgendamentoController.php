<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ApiHelper;
use App\Models\Cliente;

use App\Models\PasswordReset;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
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
            $usuario=Auth::guard('cliente')->user();
            
// dd($decodedToken);



            // Recuperar dados do token
            $cnpj = $decodedToken->cnpj;

            //E-mail do usuário que solicitou agendamento
            $email = $request->query('email_usuario');
            // dd($request);
            $email_cliente = $decodedToken->email;
            $codigoCliente = $decodedToken->codigo;
            $loja = $decodedToken->loja;

            // Excluir o registro do token do banco
            // $passwordReset = PasswordReset::where('email', $passwordReset->email)
            // ->where('token', $passwordReset->token)
            // ->delete();

            
            // Decodificar o parâmetro 'notas' da URL
            $notasParam = $request->query('aNotas');
            $notasFiltradasIds = json_decode($notasParam, true);
            // Redirecionar para a página de agendamento com os dados do token
            return redirect('/programar-entregas')->with([
                'cnpj' => $cnpj,
                'email' => $email,
                'codigoCliente' => $codigoCliente,
                'loja' => $loja,
                'notas' =>$notasFiltradasIds,
                'email_cliente' => isset($email_cliente) ? $email_cliente : ''
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
        $email_cliente = session('email_cliente');

        $usuario = Auth::guard('cliente')->user();
        $cliente = Cliente::where('cnpj', $cnpj)->where('codigo', $codigoCliente)->where('loja', $loja)->first();
        // $cliente = Cliente::where('cnpj', $cnpj)->where('codigo', '000003')->where('loja', '03')->first();
        if(!$cliente) $cliente=Cliente::where('id',1)->first();
        // Dados do cliente
        $dadosCliente = [
            'nomeFantasia' => $cliente->fantasia,
            'municipioEstado' => $cliente->municipio . '-' . $cliente->uf,
            'cnpj' => $cliente->cnpj,
            'codigoCliente' => $codigoCliente,
            'loja' => $loja,
            'email' => $email, 
            'email_cliente' =>$email_cliente
        ];
        
      
        $notasFiltradasIds  = session('notas');

        // $dadosNotasFiscais = ApiHelper::buscarNotasNaoAgendadas( '000003', '04');
        // $dadosNotasFiscais = ApiHelper::buscarNotasNaoAgendadas($cliente->codigo, $cliente->loja);
        if(!empty($notasFiltradasIds)){
            $dadosNotasFiscais = ApiHelper::buscarNotasNaoAgendadas($cliente->codigo, $cliente->loja, $notasFiltradasIds);
        }
            else
            $dadosNotasFiscais = ApiHelper::buscarNotasNaoAgendadas($cliente->codigo, $cliente->loja);
        // dd($dadosNotasFiscais);
        $logout = route('email_clientes.logout');

        
        return view('agendamento', compact('dadosCliente','dadosNotasFiscais','logout'));
    }

    public function programarEntregasAbertas()
    {

        $usuario = Auth::guard('email_clientes')->user();

        $cliente = Cliente::where('id', $usuario->cliente_id)->first();
        if (!$cliente) $cliente = Cliente::where('id', 1)->first();

        // Dados do cliente
        $dadosCliente = [
            'nomeFantasia' => $cliente->fantasia,
            'municipioEstado' => $cliente->municipio . '-' . $cliente->uf,
            'cnpj' => $cliente->cnpj,
            'codigoCliente' => $cliente->codigo,
            'loja' => $cliente->loja,
            'email' => $usuario->email, //E-mail do cliente
        ];

        // Buscar todas as notas fiscais sem agendamento
        $dadosNotasFiscais = ApiHelper::buscarNotasNaoAgendadas($cliente->codigo, $cliente->loja);
        $logout = route('email_clientes.logout');

        return view('agendartodas', compact('dadosCliente', 'dadosNotasFiscais', 'logout'));
    }

    public function confirmarAgendamento(Request $request)
    {
        $usuario = Auth::guard('email_clientes')->user();
        $cliente = Cliente::where('id', $usuario->cliente_id)->first();
        if (!$cliente) $cliente = Cliente::where('id', 1)->first();

        $dadosCliente = [
            'nomeFantasia' => $cliente->fantasia,
            'municipioEstado' => $cliente->municipio . '-' . $cliente->uf,
            'cnpj' => $cliente->cnpj,
            'codigoCliente' => $cliente->codigo,
            'loja' => $cliente->loja,
            'email' => $usuario->email,
        ];

        $notasSelecionadas = $request->input('notasSelecionadas');
        $dataAgendamento = $request->input('dataAgendamento');
        $horaAgendamento = $request->input('horaAgendamento');

        return view('confirmar_agendamento', compact('dadosCliente', 'notasSelecionadas', 'dataAgendamento', 'horaAgendamento'));
    }

    public function finalizarAgendamento(Request $request)
    {
        $usuario = Auth::guard('email_clientes')->user();
        $cliente = Cliente::where('id', $usuario->cliente_id)->first();
        if (!$cliente) $cliente = Cliente::where('id', 1)->first();

        $notasSelecionadas = $request->input('notasSelecionadas');
        $dataAgendamento = $request->input('dataAgendamento');
        $horaAgendamento = $request->input('horaAgendamento');

        $dadosAgendamento = [];
        foreach ($notasSelecionadas as $nota) {
            $dadosAgendamento[] = [
                "filial" => $nota['f2_filial'],
                "dt_agendamento" => $dataAgendamento,
                "hr_agendamento" => $horaAgendamento,
                "tipo" => "102",
                "cliente" => $nota['a1_cod'],
                "chave_nfe" => $nota['f2_chvnfe']
            ];
        }

        // Enviar e-mails
        $emailCliente = $cliente->email;
        $emailLogistica = 'logistica@lineaalimentos.com.br';

        Mail::send('emails.agendamento_cliente', compact('dadosCliente', 'notasSelecionadas', 'dataAgendamento', 'horaAgendamento'), function($message) use ($emailCliente) {
            $message->to($emailCliente)
                ->subject('Confirmação de Agendamento de Entrega');
        });

        Mail::send('emails.agendamento_logistica', compact('dadosCliente', 'notasSelecionadas', 'dataAgendamento', 'horaAgendamento'), function($message) use ($emailLogistica) {
            $message->to($emailLogistica)
                ->subject('Nova Solicitação de Agendamento de Entrega');
        });

        // Enviar dados ao Protheus via REST
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . base64_encode(env('PROTHEUS_USERNAME') . ':' . env('PROTHEUS_PASSWORD'))
        ])->post('http://www.erplineaalimentos.com.br:8191/rest/AgendarEntrega/agendamento', $dadosAgendamento);

        if ($response->successful()) {
            return redirect()->route('agendamento.sucesso');
        } else {
            return redirect()->route('agendamento.erro');
        }
    }


}
