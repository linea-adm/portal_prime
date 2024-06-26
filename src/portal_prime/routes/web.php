<?php

use App\Http\Controllers\AgendamentoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\JwtClienteController;
use App\Http\Controllers\NotaController;
use App\Http\Controllers\Auth\EmailClienteLoginController;
use App\Http\Controllers\Auth\EmailClienteRegisterController;
use App\Mail\AgendamentoMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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

// Rotas públicas
Route::get('/', function () {
    return view('agendamento');
});

Route::get('/prime', function () {
    return view('agendamento');
});

Route::get('/phpinfo', function () {
    return phpinfo();
});

// Rotas de autenticação
Auth::routes();

// Rota da página inicial após login
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Rota para geração de token JWT
Route::post('/cliente-jwt', [JwtClienteController::class, 'generateToken']);

// Rota para exibição da página de agendamento
Route::middleware(['verifyTokenUsage'])->group(function () {
    Route::get('/agendamento', [AgendamentoController::class, 'index'])->name('agendamento');
});
// Rotas para login de clientes via e-mail
Route::middleware(['guest:email_clientes'])->group(function () {
    Route::get('/cliente/login', [EmailClienteLoginController::class, 'showLoginForm'])->name('email_clientes.login');
    Route::post('/cliente/login', [EmailClienteLoginController::class, 'login'])->name('email_clientes.login.submit');
});


Route::get('/programar-entregas', [AgendamentoController::class,'programarEntregas'])->name('programarEntregas');
// Rotas para usuários autenticados (clientes)
Route::middleware(['auth.cliente'])->group(function () {
    Route::get('/programar-entregas-abertas', [AgendamentoController::class,'programarEntregasAbertas'])->name('programarEntregasAbertas');

    // Rota de teste para clientes autenticados
    Route::get('/cliente/teste', function () {
        return view('cliente.teste');
    })->name('cliente.teste');
});

// Rotas para clientes autenticados via e-mail
Route::middleware('auth:email_clientes')->group(function () {
    Route::post('/confirmar-agendamento', [AgendamentoController::class, 'confirmarAgendamento'])->name('agendamento.confirmar');
    Route::post('/finalizar-agendamento', [AgendamentoController::class, 'finalizarAgendamento'])->name('agendamento.finalizar');
    Route::post('/cliente/logout', [EmailClienteLoginController::class, 'logout'])->name('email_clientes.logout');
});

// Rota para buscar notas não agendadas
Route::get('/notas-nao-agendadas', [NotaController::class, 'buscarNotasNaoAgendadas']);

