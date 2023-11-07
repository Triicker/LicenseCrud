<?php

use App\Http\Controllers\CompanyControllerAPI;
use App\Http\Controllers\UserControllerAPI;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\UserCompanyControllerAPI;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ColigadaController;
use App\Http\Controllers\ColigLicenceController;
use App\Http\Controllers\LogCadastroController;
use App\Http\Controllers\LogLicenceController;
use App\Http\Controllers\CalculateLicenceController;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Métodos de login/logout
Route::post('auth/login', [AuthController::class, 'login']);
Route::post('auth/logout', [AuthController::class, 'logout']);
Route::post('auth/refresh', [AuthController::class, 'refresh']);
Route::get('auth/me', [AuthController::class, 'me']);

// Logs
Route::get('Zwnlogcadastro', [LogCadastroController::class, "index"]);
Route::post('Zwnlogcadastro', [LogCadastroController::class, 'logCadastro']);

Route::get('Zwnloglicenca', [LogLicenceController::class, "index"]);
Route::post('Zwnloglicenca', [LogLicenceController::class, 'logLicenca']);

Route::get('Zwnempresalayout', [LogLicenceController::class, 'layout.edit']);


// Autorização rotas
Route::middleware(['apiJWT'])->group(function () {

// LICENÇA 
Route::post('Zwnloglicenca', [CalculateLicenceController::class, 'calcularLicenca']);

// Usuarios 
Route::get('Zwnusuarios', [UserControllerAPI::class, "index"]);
Route::get('Zwnusuarios/{IDUSUARIO}', [UserControllerAPI::class, 'indexId']);
Route::post('Zwnusuarios', [UserControllerAPI::class, "store"]);
Route::patch('Zwnusuarios/{IDUSUARIO}', [UserControllerAPI::class, "update"]);
Route::delete('Zwnusuarios/{IDUSUARIO}', [UserControllerAPI::class, "delete"]);

// Empresas 
Route::get('Zwnempresas', [CompanyControllerAPI::class, "index"]);
Route::get('Zwnempresas/{IDEMPRESA}', [CompanyControllerAPI::class, "indexId"]);
Route::post('Zwnempresas', [CompanyControllerAPI::class, "store"]);
Route::patch('Zwnempresas/{IDEMPRESA}', [CompanyControllerAPI::class, "update"]);
Route::delete('Zwnempresas/{IDEMPRESA}', [CompanyControllerAPI::class, "delete"]);

// Clientes 
Route::get('Zwnclientes', [ClientController::class, "index"]);
Route::get('Zwnclientes/{IDCLIENTE}', [ClientController::class, "indexId"]);
Route::get('Zwnclientes/empresa/{IDEMPRESA}', [ClientController::class, "indexCompany"]);
Route::post('Zwnclientes', [ClientController::class, "store"]);
Route::patch('Zwnclientes/{IDCLIENTE}', [ClientController::class, "update"]);
Route::delete('Zwnclientes/{IDCLIENTE}', [ClientController::class, "delete"]);

// Contatos cliente
Route::get('Zwnclicontato', [ContactController::class, "index"]);
Route::get('Zwnclicontato/{IDCONTATO}', [ContactController::class, "indexId"]);
Route::get('Zwnclicontato/cliente/{IDCLIENTE}', [ContactController::class, "indexClient"]);
Route::post('Zwnclicontato', [ContactController::class, "store"]);
Route::patch('Zwnclicontato/{IDCONTATO}', [ContactController::class, "update"]);
Route::delete('Zwnclicontato/{IDCONTATO}', [ContactController::class, "delete"]);

// Produtos 
Route::get('Zwnproduto', [ProductController::class, "index"]);
Route::get('Zwnproduto/{IDPRODUTO}', [ProductController::class, "indexId"]);
Route::post('Zwnproduto', [ProductController::class, "store"]);
Route::patch('Zwnproduto/{IDPRODUTO}', [ProductController::class, "update"]);
Route::delete('Zwnproduto/{IDPRODUTO}', [ProductController::class, "delete"]);

// Coligadas 
Route::get('Zwncoligada', [ColigadaController::class, "index"]);
Route::get('Zwncoligada/{IDCOLIGADA}', [ColigadaController::class, "indexId"]);
Route::get('Zwncoligada/cliente/{IDCLIENTE}', [ColigadaController::class, "indexClient"]);
Route::post('Zwncoligada', [ColigadaController::class, "store"]);
Route::patch('Zwncoligada/{IDCOLIGADA}', [ColigadaController::class, "update"]);
Route::delete('Zwncoligada/{IDCOLIGADA}', [ColigadaController::class, "delete"]);

// Licencas coligadas 
Route::get('Zwncoliglicenca', [ColigLicenceController::class, "index"]);
Route::get('Zwncoliglicenca/coligada/{IDCOLIGADA}', [ColigLicenceController::class, "index"]);
Route::get('Zwncoliglicenca/produto/{IDPRODUTO}', [ColigLicenceController::class, "index"]);
Route::get('Zwncoliglicenca/cliente/{IDCLIENTE}', [ColigLicenceController::class, "index"]);
Route::post('Zwncoliglicenca', [ColigLicenceController::class, "store"]);
Route::patch('Zwncoliglicenca/{IDCOLIGADA}', [ColigLicenceController::class, "update"]);
Route::delete('Zwncoliglicenca/{IDCOLIGADA}', [ColigLicenceController::class, "delete"]);

// Usuarios Empresa 
Route::get('Zwnusuempresas', [UserCompanyControllerAPI::class, "index"]);
Route::get('Zwnusuempresas{IDUSUARIOEMPRESA}', [UserCompanyControllerAPI::class, "indexId"]);
Route::post('Zwnusuempresas', [UserCompanyControllerAPI::class, "store"]);
Route::put('Zwnusuempresas/{IDUSUARIOEMPRESA}', [UserCompanyControllerAPI::class, "update"]);
Route::delete('Zwnusuempresas/{IDUSUARIOEMPRESA}', [UserCompanyControllerAPI::class, "delete"]);

});




