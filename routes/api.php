<?php

use App\Http\Controllers\CompanyControllerAPI;
use App\Http\Controllers\UserControllerAPI;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\UserCompanyControllerAPI;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ColigadaController;
use App\Http\Controllers\ColigLicenseController;
use App\Http\Controllers\LogCadastroController;
use App\Http\Controllers\LogLicenseController;
use App\Http\Controllers\LogLicenseItemController;
use App\Http\Controllers\CalculateLicenseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Métodos de login/logout
Route::post('auth/login', [AuthController::class, 'login']);
Route::post('auth/logout', [AuthController::class, 'logout']);
Route::post('auth/refresh', [AuthController::class, 'refresh']);
Route::get('auth/me', [AuthController::class, 'me']);

// Autorização rotas
Route::middleware(['apiJWT'])->group(function () {

// Logs
Route::get('Zwnlogcadastro', [LogCadastroController::class, "index"]);
Route::post('Zwnlogcadastro', [LogCadastroController::class, 'logCadastro']);

Route::get('Zwnloglicencaitem', [LogLicenseItemController::class, "index"]);
Route::post('Zwnloglicencaitem', [LogLicenseItemController::class, 'logLicencaItem']);


Route::get('Zwnloglicenca', [LogLicenseController::class, "index"]);
Route::post('Zwnloglicenca', [LogLicenseController::class, 'logLicenca']);
Route::get('Zwnempresalayout', [LogLicenseController::class, 'layout.edit']);    

// LICENÇA 
Route::post('Zwnloglicenca', [CalculateLicenseController::class, 'calcularLicenca']);

// Usuarios 
Route::get('Zwnusuarios', [UserControllerAPI::class, "index"]);
Route::get('Zwnusuarios/{IDUSUARIO}', [UserControllerAPI::class, 'indexId']);
Route::patch('Zwnusuarios/{IDUSUARIO}', [UserControllerAPI::class, "update"]);
Route::post('Zwnusuarios', [UserControllerAPI::class, "store"]);
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
Route::get('Zwncoliglicenca', [ColigLicenseController::class, "index"]);
Route::get('Zwncoliglicenca/coligada/{IDCOLIGADA}', [ColigLicenseController::class, "index"]);
Route::get('Zwncoliglicenca/produto/{IDPRODUTO}', [ColigLicenseController::class, "index"]);
Route::get('Zwncoliglicenca/cliente/{IDCLIENTE}', [ColigLicenseController::class, "index"]);
Route::post('Zwncoliglicenca', [ColigLicenseController::class, "store"]);
Route::patch('Zwncoliglicenca/{IDCOLIGADA}', [ColigLicenseController::class, "update"]);
Route::delete('Zwncoliglicenca/{IDCOLIGADA}', [ColigLicenseController::class, "delete"]);

// Usuarios Empresa 
Route::get('Zwnusuempresas', [UserCompanyControllerAPI::class, "index"]);
Route::get('Zwnusuempresas{IDUSUARIOEMPRESA}', [UserCompanyControllerAPI::class, "indexId"]);
Route::post('Zwnusuempresas', [UserCompanyControllerAPI::class, "store"]);
Route::put('Zwnusuempresas/{IDUSUARIOEMPRESA}', [UserCompanyControllerAPI::class, "update"]);
Route::delete('Zwnusuempresas/{IDUSUARIOEMPRESA}', [UserCompanyControllerAPI::class, "delete"]);

});




