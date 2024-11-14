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
Route::resource('teste', UsuarioController::class)->middleware('auth');
Route::get('teste.permissao/{id}', [UsuarioController::class, 'permissao'])->name('teste.permissao');
Route::resource('veiculos', VeiculoController::class);
Route::patch('/veiculos/{veiculo}/status', [VeiculoController::class, 'mudarStatus'])->name('veiculos.mudarStatus')->middleware('auth');
Route::post('/veiculos/{veiculo}/funcionamento', [VeiculoController::class, 'mudarStatus']);
Route::post('/veiculos/{id}/mudarStatus', [VeiculoController::class, 'mudarStatus'])->name('veiculos.mudarStatus');
Route::get('solicitar/create/{id}', [VeiculoController::class, 'solicitarCarro'])->name('solicitar.create');
Route::get('solicitar', [VeiculoController::class, 'solicitarIndex'])->name('solicitar.index');
Route::get('solicitar/{id}', [SolicitarController::class, 'show'])->name('solicitar.show');
Route::post('solicitar/store', [SolicitarController::class, 'store'])->name('solicitar.store');
// Route::get('solicitar/create', [SolicitarController::class], 'solicitarCarro')->name('solicitar.create');


// Route::resource('solicitar', SolicitarController::class);