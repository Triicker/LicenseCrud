<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;

Route::get('/login', function () {return view('login');})->name('login');
Route::post('/login-web', [AuthController::class, 'loginWeb'])->name('auth.loginWeb');
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

Route::get('/', [IndexController::class, 'index'])->name('index');
// Client
Route::get('/clientes/create', [ClientController::class, 'create'])->name('clientes.create');
Route::get('/clientes', [ClientController::class, 'index'])->name('clientes.index');
Route::post('/clientes', [ClientController::class, 'store'])->name('clientes.store');
//Route::get('/clientes/{IDCLIENTE}', [ClientController::class, 'show'])->name('clientes.show');
Route::get('/clientes/{IDCLIENTE}/edit', [ClientController::class, 'edit'])->name('clientes.edit');
Route::put('/clientes/{IDCLIENTE}', [ClientController::class, 'update'])->name('clientes.update');
Route::delete('/clientes/{IDCLIENTE}', [ClientController::class, 'delete'])->name('clientes.delete');
Route::post('/clientes/{IDCLIENTE}/delete', [ClientController::class, 'delete'])->name('clientes.delete-web');
// Contact
Route::get('/contatos', [ContactController::class, "index"])->name('contatos.index');
Route::get('/contatos/create', [ContactController::class, 'create'])->name('contatos.create');
Route::post('/contatos', [ContactController::class, 'store'])->name('contatos.store');
//Route::get('/contatos/{IDCONTATO}', [ContactController::class, "showContact"])->name('contatos.showContact');
Route::get('/contatos/{IDCONTATO}/edit', [ContactController::class, "edit"])->name('contatos.edit');
Route::put('/contatos/{IDCONTATO}', [ContactController::class, 'update'])->name('contatos.update');
Route::delete('/contatos/{IDCONTATO}', [ContactController::class, 'delete'])->name('contatos.delete');
Route::post('/contatos/{IDCONTATO}/delete', [ContactController::class, 'delete'])->name('contatos.delete-web');




