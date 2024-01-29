<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AgendamentoController;
use Illuminate\Support\Facades\Gate;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthController::class, 'login']);

// Route::middleware(['auth:sanctum'])->post('/agendamento/gerar-link', [AgendamentoController::class, 'gerarLink']);



Route::middleware(['auth:sanctum'])->post(
                        '/agendamento/gerar-link',
                        [AgendamentoController::class, 'gerarLink']
                                        )->name('agendamento')
                                        ->withoutMiddleware(['signed']);

Route::middleware(['signed'])->group(function () {
    Route::get('/agendamento', [AgendamentoController::class, 'processarLink']);
});

Route::middleware(['auth:sanctum'])->get('/testar-link-assinado', [AgendamentoController::class, 'testarLinkAssinado']);
