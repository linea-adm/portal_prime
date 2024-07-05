<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AgendamentoController;
use Illuminate\Support\Facades\Gate;
use Laravel\Sanctum\Http\Controllers;
use App\Http\Controllers\SendLoginLinkController;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Http;

use App\Http\Controllers\EmailClienteController;
use App\Mail\AgendamentoMail;
use Illuminate\Support\Facades\Mail;

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

Route::get('/notas-nao-agendadas-filtradas', function (Request $request) {
    $token = env('SERVICE_GOBI_TOKEN');
    $baseUrl = rtrim(env('SERVICE_GOBI_URL_NOTAS_PARA_AGENDAMENTO_FILTRADAS'), '&');
    $url = $baseUrl . '&filtro_cliente=' . $request->filtro_cliente. 'filtro_loja=' . $request->filtro_cliente.'filtro_notas='.$request->filtro_notas;

    $response = Http::withToken($token)->get($url);

    return response()->json($response->json());
});

Route::post('/emails/create', [EmailClienteController::class, 'create']);

Route::get('/detalhes-pedido', [ApiController::class, 'buscarDetalhesPedido']);

// Rota para enviar confirmação de agendamento via e-mail
Route::post('/enviar-confirmacao-agendamento', function (Request $request) {
    $detalhes = [
        'data' => $request->input('data'),
        'hora' => $request->input('hora'),
        'notas' => $request->input('notas')
    ];

    $emailCliente = $request->input('emailCliente');
    $emailLogistica = env('LOGISTICA_EMAIL');

    Mail::to($emailCliente)->send(new AgendamentoMail($detalhes));
    Mail::to($emailLogistica)->send(new AgendamentoMail($detalhes));

    return response()->json(['message' => 'E-mails de confirmação enviados com sucesso.']);
});

Route::get('/logistica-email', function () {
    return response()->json(['email' => env('LOGISTICA_EMAIL')]);
});


// Route::middleware('auth:api')->post('/agendar-entrega', [ApiController::class, 'agendarEntrega']);
