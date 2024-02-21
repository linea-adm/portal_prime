<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AgendamentoController;
use Illuminate\Support\Facades\Gate;
use Laravel\Sanctum\Http\Controllers;
use App\Http\Controllers\SendLoginLinkController;
use App\Http\Controllers\ApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::post('/login', [Controllers\AuthorizedAccessTokenController::class, 'store']);
// Route::post('/logout', [Controllers\AuthenticatedSessionController::class, 'destroy']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthController::class, 'loginProtheus']);

// Route::middleware(['auth:sanctum'])->post('/agendamento/gerar-link', [AgendamentoController::class, 'gerarLink']);

// Route::middleware(['auth:sanctum'])->post(
//                         '/agendamento/gerar-link',
//                         [AgendamentoController::class, 'gerarLink']
//                                         )->name('agendamento')
//                                         ->withoutMiddleware(['signed']);
Route::middleware(['auth:sanctum'])->post('/agendamento/gerar-link', [SendLoginLinkController::class, 'send']);
Route::middleware(['signed'])->group(function () {
    Route::get('/agendamento', [AgendamentoController::class, 'processarLink']);
});

Route::middleware(['auth:sanctum'])->get('/testar-link-assinado', [AgendamentoController::class, 'testarLinkAssinado']);


Route::get('/notas-nao-agendadas', function (Request $request) {
    $token = env('SERVICE_GOBI_TOKEN');
    $baseUrl = rtrim(env('SERVICE_GOBI_URL_NOTAS_PARA_AGENDAMENTO'), '&');
    $url = $baseUrl . '&filtro_cliente=' . $request->filtro_cliente. 'filtro_loja=' . $request->filtro_cliente;

    $response = Http::withToken($token)->get($url);

    return response()->json($response->json());
});



Route::get('/detalhes-pedido', [ApiController::class, 'buscarDetalhesPedido']);
