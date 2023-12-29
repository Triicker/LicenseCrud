<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ColigadaController;
use App\Http\Controllers\ColigLicenseController;
use App\Http\Controllers\LogLicenseController;
use App\Http\Controllers\LayoutController;

// index
Route::get('/', [IndexController::class, 'index'])->name('index');

// login
Route::get('/login', function () {return view('login');})->name('login');
Route::post('/login-web', [AuthController::class, 'loginWeb'])->name('auth.loginWeb');
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
 
// clientes
Route::get('/clientes', [ClientController::class, 'index'])->name('clientes.index');
Route::get('/clientes/create', [ClientController::class, 'create'])->name('clientes.create');
Route::post('/clientes', [ClientController::class, 'store'])->name('clientes.store');
Route::get('/clientes/{IDCLIENTE}/edit', [ClientController::class, 'edit'])->name('clientes.edit');
Route::put('/clientes/{IDCLIENTE}', [ClientController::class, 'update'])->name('clientes.update');
Route::delete('/clientes/{IDCLIENTE}', [ClientController::class, 'delete'])->name('clientes.delete');
Route::post('/clientes/{IDCLIENTE}/delete', [ClientController::class, 'delete'])->name('clientes.delete-web');

// contatos
Route::get('/contatos', [ContactController::class, "index"])->name('contatos.index');
Route::get('/clientes/{IDCLIENTE}/contatos', [ContactController::class, 'indexClient'])->name('clientes.contatos');
Route::get('/clientes/{IDCLIENTE}/contatos/create', [ContactController::class, 'create'])->name('clientes.contatos.create');
Route::post('/clientes/{IDCLIENTE}/contatos', [ContactController::class, 'store'])->name('clientes.contatos.store');
Route::get('/clientes/{IDCLIENTE}/contatos/{IDCONTATO}/edit', [ContactController::class, "edit"])->name('clientes.contatos.edit');
Route::put('/clientes/{IDCLIENTE}/contatos/{IDCONTATO}', [ContactController::class, 'update'])->name('clientes.contatos.update');
Route::delete('/clientes/{IDCLIENTE}/contatos/{IDCONTATO}', [ContactController::class, 'delete'])->name('clientes.contatos.delete');
Route::post('/clientes/{IDCLIENTE}/contatos/{IDCONTATO}/delete', [ContactController::class, 'delete'])->name('clientes.contatos.delete-web');

// coligadas
Route::get('/coligadas', [ColigadaController::class, 'index'])->name('coligadas.index');
Route::get('/clientes/{IDCLIENTE}/coligadas', [ColigadaController::class, 'indexClient'])->name('clientes.coligadas');
Route::get('/clientes/{IDCLIENTE}/coligadas/create', [ColigadaController::class, 'create'])->name('clientes.coligadas.create');
Route::post('/clientes/{IDCLIENTE}/coligadas', [ColigadaController::class, 'store'])->name('clientes.coligadas.store');
Route::get('/clientes/{IDCLIENTE}/coligadas/{IDCOLIGADA}/edit', [ColigadaController::class, 'edit'])->name('clientes.coligadas.edit');
Route::patch('/clientes/{IDCLIENTE}/coligadas/{IDCOLIGADA}', [ColigadaController::class, 'update'])->name('clientes.coligadas.update');
Route::delete('/clientes/{IDCLIENTE}/coligadas/{IDCOLIGADA}', [ColigadaController::class, 'delete'])->name('clientes.coligadas.delete');
Route::post('/clientes/{IDCLIENTE}/coligadas/{IDCOLIGADA}/delete', [ColigadaController::class, 'delete'])->name('clientes.coligadas.delete-web');

// license
Route::get('/licencas', [ColigLicenseController::class, 'index'])->name('licencas.index');
Route::get('/clientes/{IDCLIENTE}/coligadas/{IDCOLIGADA}/licencas', [ColigLicenseController::class, 'indexClient'])->name('coligadas.licencas');
Route::get('/clientes/{IDCLIENTE}/coligadas/{IDCOLIGADA}/licencas/create', [ColigLicenseController::class, 'create'])->name('coligadas.licencas.create');
Route::post('/clientes/{IDCLIENTE}/coligadas/{IDCOLIGADA}/licencas', [ColigLicenseController::class, 'store'])->name('coligadas.licencas.store');
Route::get('/clientes/{IDCLIENTE}/coligadas/{IDCOLIGADA}/licencas/{IDPRODUTO}/edit', [ColigLicenseController::class, 'edit'])->name('coligadas.licencas.edit');
Route::put('/clientes/{IDCLIENTE}/coligadas/{IDCOLIGADA}/licencas/{IDPRODUTO}', [ColigLicenseController::class, 'update'])->name('coligadas.licencas.update');
Route::delete('/clientes/{IDCLIENTE}/coligadas/{IDCOLIGADA}/licencas/{IDPRODUTO}', [ColigLicenseController::class, 'delete'])->name('coligadas.licencas.delete');
Route::post('/clientes/{IDCLIENTE}/coligadas/{IDCOLIGADA}/licencas/{IDPRODUTO}/delete', [ColigLicenseController::class, 'delete'])->name('coligadas.licencas.delete-web');

// produtos
Route::get('/produtos', [ProductController::class, 'index'])->name('produtos.index');
Route::get('/produtos/create', [ProductController::class, 'create'])->name('produtos.create');
Route::post('/produtos', [ProductController::class, 'store'])->name('produtos.store');
Route::get('/produtos/{IDPRODUTO}/edit', [ProductController::class, 'edit'])->name('produtos.edit');
Route::put('/produtos/{IDPRODUTO}', [ProductController::class, 'update'])->name('produtos.update');
Route::delete('/produtos/{IDPRODUTO}', [ProductController::class, 'delete'])->name('produtos.delete');
Route::post('/produtos/{IDPRODUTO}/delete', [ProductController::class, 'delete'])->name('produtos.delete-web');

// logs
Route::get('/logs', [LogLicenseController::class, 'index'])->name('logs.index');

// layout
Route::get('/layout', [LayoutController::class, 'index'])->name('layout.edit');

