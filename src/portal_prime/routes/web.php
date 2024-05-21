<?php

use App\Http\Controllers\AgendamentoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\JwtClienteController;
use App\Http\Controllers\NotaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('agendamento');
});
Route::get('/prime', function () {
    return view('agendamento');
});

Route::get('/phpinfo', function () {
    return phpinfo();
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// routes\web.php


Route::post('/cliente-jwt', [JwtClienteController::class, 'generateToken']);


Route::get('/agendamento', [AgendamentoController::class, 'index'])->middleware('verifyTokenUsage');

Route::middleware(['cliente'])->group(function () {

    Route::get('/programar-entregas', [AgendamentoController::class,'programarEntregas'])->name('programarEntregas');

});


Route::get('/notas-nao-agendadas', [NotaController::class, 'buscarNotasNaoAgendadas']);
