<?php

namespace App\Http\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

 Class ApiHelper {

    public static function buscarNotasNaoAgendadas($filtro_cliente, $filtro_loja, $notasFiltradasIds = null)
    {
        $token = env('SERVICE_GOBI_TOKEN');
        $baseUrl = rtrim(env('SERVICE_GOBI_URL_NOTAS_PARA_AGENDAMENTO_SEM_FILTRO'), '&');
        $url = $baseUrl . '&filtro_cliente=' . $filtro_cliente. '&filtro_loja=' . $filtro_loja;
        $notasFiltradasIds=json_decode($notasFiltradasIds, true);
        // dd($url);
        // Se existirem IDs de notas para filtrar, adicione-os à URL
        if (!is_null($notasFiltradasIds) && is_array($notasFiltradasIds)) {
            $ids = implode(',', array_keys($notasFiltradasIds));
            $baseUrl = rtrim(env('SERVICE_GOBI_URL_NOTAS_PARA_AGENDAMENTO'), '&');
            $url = $baseUrl . '&filtro_cliente=' . $filtro_cliente. '&filtro_loja=' . $filtro_loja;
            $url .= '&filtro_notas=' . $ids; 
        }

        $response = Http::withToken($token)->get($url);
// dd($response->json());
        return $response->json();
    }
    public static function buscarDetalhesPedido($filtroCliente, $filtroLoja, $filtroNota)
    {
        $token = env('SERVICE_GOBI_TOKEN');
        $url = env('SERVICE_GOBI_URL_DETALHES_PEDIDO') . '&filtro_cliente=' . $filtroCliente . '&filtro_loja=' . $filtroLoja. '&filtro_nota=' . $filtroNota;

        $response = Http::withToken($token)->get($url);

        return $response->json();
    }

}
