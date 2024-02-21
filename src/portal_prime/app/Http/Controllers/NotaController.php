<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NotaController extends Controller
{
    public function buscarNotasNaoAgendadas(Request $request)
    {
        $token = env('SERVICE_GOBI_TOKEN');
        $baseUrl = rtrim(env('SERVICE_GOBI_URL_NOTAS_PARA_AGENDAMENTO'), '&');
        $url = $baseUrl . '&filtro_cliente=' . $request->filtro_cliente. '&filtro_loja=' . $request->filtro_loja;

        $response = Http::withToken($token)->get($url);

        // Processar os dados conforme necessário
        $notasNaoAgendadas = $response->json();

        // Retornar os dados para o frontend
        return response()->json($notasNaoAgendadas);
    }
}
