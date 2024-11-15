<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\VeiculoController;
use App\Http\Controllers\SolicitarController;
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
    return view('welcome');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
//USUÁRIOS
Route::resource('teste', UsuarioController::class)->middleware('auth');
Route::get('teste.permissao/{id}', [UsuarioController::class, 'permissao'])->name('teste.permissao');
//VEÍCULOS
Route::resource('veiculos', VeiculoController::class)->middleware('auth');
Route::post('/veiculos/{id}/mudarStatus', [VeiculoController::class, 'mudarStatus'])->name('veiculos.mudarStatus');
//SOLICITAÇÕES:
Route::get('solicitar', [VeiculoController::class, 'solicitarIndex'])->name('solicitar.index');
Route::get('solicitar/create/{id}', [VeiculoController::class, 'solicitarCarro'])->name('solicitar.create');
// Route::get('solicitar/create', [SolicitarController::class], 'solicitarCarro')->name('solicitar.create');
Route::post('solicitar/store', [SolicitarController::class, 'store'])->name('solicitar.store');
Route::get('solicitar/{id}', [SolicitarController::class, 'show'])->name('solicitar.show');
Route::get('solicitação', [SolicitarController::class, 'index'])->name('solicitacao.index');
Route::get('solicitar/ver/{id}', [SolicitarController::class, 'ver'])->name('solicitar.ver');
Route::put('/solicitar/situacao/{id}', [SolicitarController::class, 'update'])->name('solicitar.update');


// Route::resource('solicitar', SolicitarController::class);