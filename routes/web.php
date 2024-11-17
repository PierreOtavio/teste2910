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

Route::post('solicitar/store', [SolicitarController::class, 'store'])->name('solicitar.store');

Route::get('solicitar/{id}', [SolicitarController::class, 'index'])->name('solicitar.show');

Route::get('solicitar/ver/{id}', [SolicitarController::class, 'ver'])->name('solicitar.ver');

Route::get('solicitar/start/{id}', [SolicitarController::class, 'start'])->name('solicitar.start');

Route::post('solicitar/prosseguir/{id}', [SolicitarController::class, 'prosseguir'])->name('solicitar.prosseguir');


Route::get('solicitar/end/{id}', [SolicitarController::class, 'end'])->name('solicitar.end');
//Teste de aceitar e recusar:
Route::post('/solicitar/{id}/aceitar', [SolicitarController::class, 'aceitar'])->name('solicitar.aceitar');
Route::post('/solicitar/{id}/recusar', [SolicitarController::class, 'recusar'])->name('solicitar.recusar');
