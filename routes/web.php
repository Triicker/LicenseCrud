<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ColigadaController;
use App\Http\Controllers\ColigLicenseController;
use App\Http\Controllers\LogCadastroController;
use App\Http\Controllers\LogLicenseController;
use App\Http\Controllers\LayoutController;

use Illuminate\Support\Facades\Auth;

Route::get('/login', function () {return view('login');})->name('login');
Route::post('/login-web', [AuthController::class, 'loginWeb'])->name('auth.loginWeb');
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

Route::get('/', [IndexController::class, 'index'])->name('index');
// Client
Route::get('/clientes/create', [ClientController::class, 'create'])->name('clientes.create');
Route::get('/clientes', [ClientController::class, 'index'])->name('clientes.index');
Route::post('/clientes', [ClientController::class, 'store'])->name('clientes.store');
Route::get('/clientes/{IDCLIENTE}/edit', [ClientController::class, 'edit'])->name('clientes.edit');
Route::put('/clientes/{IDCLIENTE}', [ClientController::class, 'update'])->name('clientes.update');
Route::delete('/clientes/{IDCLIENTE}', [ClientController::class, 'delete'])->name('clientes.delete');
Route::post('/clientes/{IDCLIENTE}/delete', [ClientController::class, 'delete'])->name('clientes.delete-web');
// Contact
Route::get('/contatos', [ContactController::class, "index"])->name('contatos.index');
Route::get('/contatos/cliente/{IDCLIENTE}', [ContactController::class, 'indexClient'])->name('contatos.cliente');
Route::get('/contatos/create', [ContactController::class, 'create'])->name('contatos.create');
Route::post('/contatos', [ContactController::class, 'store'])->name('contatos.store');
Route::get('/contatos/{IDCONTATO}/edit', [ContactController::class, "edit"])->name('contatos.edit');
Route::put('/contatos/{IDCONTATO}', [ContactController::class, 'update'])->name('contatos.update');
Route::delete('/contatos/{IDCONTATO}', [ContactController::class, 'delete'])->name('contatos.delete');
Route::post('/contatos/{IDCONTATO}/delete', [ContactController::class, 'delete'])->name('contatos.delete-web');
// Product
Route::get('/produtos', [ProductController::class, 'index'])->name('produtos.index');
Route::get('/produtos/create', [ProductController::class, 'create'])->name('produtos.create');
Route::post('/produtos', [ProductController::class, 'store'])->name('produtos.store');
Route::get('/produtos/{IDPRODUTO}/edit', [ProductController::class, 'edit'])->name('produtos.edit');
Route::put('/produtos/{IDPRODUTO}', [ProductController::class, 'update'])->name('produtos.update');
Route::delete('/produtos/{IDPRODUTO}', [ProductController::class, 'delete'])->name('produtos.delete');
Route::post('/produtos/{IDPRODUTO}/delete', [ProductController::class, 'delete'])->name('produtos.delete-web');
// Coligada
Route::get('/coligadas', [ColigadaController::class, 'index'])->name('coligadas.index');
Route::get('/coligadas/cliente/{IDCLIENTE}', [ColigadaController::class, 'indexClient'])->name('coligadas.cliente');
Route::get('/coligadas/create', [ColigadaController::class, 'create'])->name('coligadas.create');
Route::post('/coligadas', [ColigadaController::class, 'store'])->name('coligadas.store');
Route::get('/coligadas/{IDCOLIGADA}/edit', [ColigadaController::class, 'edit'])->name('coligadas.edit');
Route::patch('/coligadas/{IDCOLIGADA}', [ColigadaController::class, 'update'])->name('coligadas.update');
Route::delete('/coligadas/{IDCOLIGADA}', [ColigadaController::class, 'delete'])->name('coligadas.delete');
Route::post('/coligadas/{IDCOLIGADA}/delete', [ColigadaController::class, 'delete'])->name('coligadas.delete-web');
// license
Route::get('/licencas', [ColigLicenseController::class, 'index'])->name('licencas.index');
Route::get('/licencas/coligada/{IDCOLIGADA}/cliente/{IDCLIENTE?}/produto/{IDPRODUTO?}', [ColigLicenseController::class, 'index'])->name('licencas.coligada');
Route::post('/licencas', [ColigLicenseController::class, 'store'])->name('licencas.store');
Route::get('/licencas/create', [ColigLicenseController::class, 'create'])->name('licencas.create');
Route::get('/licencas/{IDCOLIGADA}/edit', [ColigLicenseController::class, 'edit'])->name('licencas.edit');
Route::put('/licencas/{IDCOLIGADA}', [ColigLicenseController::class, 'update'])->name('licencas.update');
Route::delete('/licencas/{IDCOLIGADA}', [ColigLicenseController::class, 'delete'])->name('licencas.delete');
Route::post('/licencas/{IDCOLIGADA}/delete', [ColigLicenseController::class, 'delete'])->name('licencas.delete-web');
// logs
Route::get('/logs', [LogLicenseController::class, 'index'])->name('logs.index');

Route::get('/layout', [LayoutController::class, 'index'])->name('layout.edit');

