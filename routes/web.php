<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\VeiculoController;
use App\Http\Controllers\SolicitarController;
use App\Http\Controllers\NotificationController;
use App\Models\Solicitar;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();
Route::get('/', function () {
    return view('auth.login');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//USUÁRIOS
Route::resource('teste', UsuarioController::class)->middleware('auth');

Route::get('teste.permissao/{id}', [UsuarioController::class, 'permissao'])->name('teste.permissao')->middleware('auth');

Route::post('/teste/{id}/mudarStatusU', [UsuarioController::class, 'mudarStatusU'])->name('teste.mudarStatusU')->middleware('auth');

//VEÍCULOS
Route::resource('veiculos', VeiculoController::class)->middleware('auth');

Route::post('/veiculos/{id}/mudarStatus', [VeiculoController::class, 'mudarStatus'])->name('veiculos.mudarStatus')->middleware('auth');

//SOLICITAÇÕES:
Route::get('solicitar', [VeiculoController::class, 'solicitarIndex'])->name('solicitar.index')->middleware('auth');

Route::get('solicitar/create/{id}', [VeiculoController::class, 'solicitarCarro'])->name('solicitar.create')->middleware('auth');

Route::post('solicitar/store', [SolicitarController::class, 'store'])->name('solicitar.store')->middleware('auth');

Route::get('solicitar/{id}', [SolicitarController::class, 'index'])->name('solicitar.show')->middleware('auth');

Route::get('solicitar/ver/{id}', [SolicitarController::class, 'ver'])->name('solicitar.ver')->middleware('auth');

Route::get('solicitar/start/{id}', [SolicitarController::class, 'start'])->name('solicitar.start')->middleware('auth');

Route::post('solicitar/prosseguir/{id}', [SolicitarController::class, 'prosseguir'])->name('solicitar.prosseguir')->middleware('auth');

Route::get('solicitar/end/{id}', [SolicitarController::class, 'end'])->name('solicitar.end')->middleware('auth');

Route::post('solicitar/finalizar/{id}', [SolicitarController::class, 'finalizar'])->name('solicitar.finalizar')->middleware('auth');

Route::match(['get', 'post'], '/solicitar/{id}/aceitar', [SolicitarController::class, 'aceitar'])->name('solicitar.aceitar')->middleware('auth');

Route::post('/solicitar/{id}/motivoRecusado', [SolicitarController::class, 'motivoRecusado'])->name('solicitar.motivoRecusado')->middleware('auth');

Route::post('/solicitar/{id}/recusar', [SolicitarController::class, 'recusar'])->name('solicitar.recusar')->middleware('auth');

Route::get('solicitar/finalizadas/{id}', [SolicitarController::class, 'finalizadas'])->name('solicitar.finalizadas')->middleware('auth');

Route::get('/gerar-pdf/{id}', [SolicitarController::class, 'gerarPDF'])->name('gerar.pdf')->middleware('auth');

Route::get('/exportar-excel/{id}', [SolicitarController::class, 'exportarExcel'])->name('exportar.excel')->middleware('auth');

Route::get('/exportar-todas-excel', [SolicitarController::class, 'exportarTodasExcel'])->name('exportar.todas.excel')->middleware('auth');

Route::get('/solicitar/recusadas/{id}', [SolicitarController::class, 'solicitacoesRecusadas'])->name('solicitar.solicitacoesRecusadas')->middleware('auth');

Route::get('solicitar/ver-recusadas/{id}', [SolicitarController::class, 'verRecusada'])->name('solicitar.verrecusada')->middleware('auth');

Route::get('/notificacoes', [NotificationController::class, 'index'])->name('solicitar.notificacoes');


//NOTIFICAÇÕES
Route::get('/notificacoes/contar', [NotificationController::class, 'count']);

Route::get('/notificacoes/listar', [NotificationController::class, 'list']);

Route::patch('/notificacoes/{id}/marcar-como-lida', [NotificationController::class, 'markAsRead'])->name('notificacoes.marcarComoLida');


