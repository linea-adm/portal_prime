<?php

namespace App\Http\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

 Class ApiHelper {


    public static function buscarNotasNaoAgendadas($filtro_cliente, $filtro_loja)
    {
        $token = env('SERVICE_GOBI_TOKEN');
        $baseUrl = rtrim(env('SERVICE_GOBI_URL_NOTAS_PARA_AGENDAMENTO'), '&');
        $url = $baseUrl . '&filtro_cliente=' . $filtro_cliente. '&filtro_loja=' . $filtro_loja;

        $response = Http::withToken($token)->get($url);

        // Processar os dados conforme necessário
        // $notasNaoAgendadas = $response->json();

        // Retornar os dados para o frontend
        return $response->json(); //response()->json($notasNaoAgendadas);
    }
    public static function buscarDetalhesPedido($filtroCliente, $filtroLoja, $filtroNota)
    {
        $token = env('SERVICE_GOBI_TOKEN');
        $url = env('SERVICE_GOBI_URL_DETALHES_PEDIDO') . '&filtro_cliente=' . $filtroCliente . '&filtro_loja=' . $filtroLoja. '&filtro_nota=' . $filtroNota;

        $response = Http::withToken($token)->get($url);

        return $response->json();
    }

}
