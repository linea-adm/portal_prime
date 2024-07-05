<?php
namespace App\Http\Controllers;

use App\Http\Helpers\ApiHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiController extends Controller
{
    public function buscarDetalhesPedido(Request $request)
    {
        $filtroCliente = $request->input('filtro_cliente');
        $filtroLoja = $request->input('filtro_loja');
        $filtroNota = $request->input('filtro_nota');

        return ApiHelper::buscarDetalhesPedido($filtroCliente, $filtroLoja, $filtroNota);
    }


    public function agendarEntrega(Request $request)
    {
        $validated = $request->validate([
            'filial' => 'required|string',
            'dt_agendamento' => 'required|date_format:Ymd',
            'hr_agendamento' => 'required|date_format:Hi',
            'tipo' => 'required|string',
            'cliente' => 'required|string',
            'chave_nfe' => 'required|string',
        ]);

        $agendamentoDados = [
            'filial' => $validated['filial'],
            'dt_agendamento' => $validated['dt_agendamento'],
            'hr_agendamento' => $validated['hr_agendamento'],
            'tipo' => $validated['tipo'],
            'cliente' => $validated['cliente'],
            'chave_nfe' => $validated['chave_nfe'],
        ];

        $username = env('REST_API_USERNAME');
        $password = env('REST_API_PASSWORD');
        $authHeader = 'Basic ' . base64_encode("$username:$password");

        $response = Http::withHeaders(['Authorization' => $authHeader])
                        ->post('https://www.erplineaalimentos.com.br:8191/rest/AgendarEntrega/agendamento', $agendamentoDados);

        if ($response->successful()) {
            return response()->json(['message' => 'Agendamento realizado com sucesso.']);
        } else {
            return response()->json(['error' => 'Erro ao agendar a entrega.', 'details' => $response->body()], $response->status());
        }
    }

}
