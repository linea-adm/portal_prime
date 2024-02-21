<?php
namespace App\Http\Controllers;

use App\Http\Helpers\ApiHelper;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function buscarDetalhesPedido(Request $request)
    {
        $filtroCliente = $request->input('filtro_cliente');
        $filtroLoja = $request->input('filtro_loja');
        $filtroNota = $request->input('filtro_nota');

        return ApiHelper::buscarDetalhesPedido($filtroCliente, $filtroLoja, $filtroNota);
    }
}
