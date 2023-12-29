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

    // Valida Licença
    Route::post('Zwnloglicenca', [CalculateLicenseController::class, 'calcularLicenca']);

    // Usuarios 
    Route::get('usuarios', [UserControllerAPI::class, "index"]);
    Route::get('usuarios/{IDUSUARIO}', [UserControllerAPI::class, 'indexId']);
    Route::patch('usuarios/{IDUSUARIO}', [UserControllerAPI::class, "update"]);
    Route::post('usuarios', [UserControllerAPI::class, "store"]);
    Route::delete('usuarios/{IDUSUARIO}', [UserControllerAPI::class, "delete"]);

    // Empresas 
    Route::get('empresas', [CompanyControllerAPI::class, "index"]);
    Route::get('empresas/{IDEMPRESA}', [CompanyControllerAPI::class, "indexId"]);
    Route::post('empresas', [CompanyControllerAPI::class, "store"]);
    Route::patch('empresas/{IDEMPRESA}', [CompanyControllerAPI::class, "update"]);
    Route::delete('empresas/{IDEMPRESA}', [CompanyControllerAPI::class, "delete"]);

    // Clientes 
    Route::get('clientes', [ClientController::class, "index"]);
    Route::get('clientes/{IDCLIENTE}', [ClientController::class, "indexId"]);
    //Route::get('empresas/{IDEMPRESA}/clientes', [ClientController::class, "indexCompany"]);
    Route::post('clientes', [ClientController::class, "store"]);
    Route::patch('clientes/{IDCLIENTE}', [ClientController::class, "update"]);
    Route::delete('clientes/{IDCLIENTE}', [ClientController::class, "delete"]);

    // Contatos cliente
    Route::get('contatos', [ContactController::class, "index"]);
    Route::get('clientes/{IDCLIENTE}/contatos/{IDCONTATO}', [ContactController::class, "indexId"]);
    Route::get('clientes/{IDCLIENTE}/contatos', [ContactController::class, "indexClient"]);
    Route::post('clientes/{IDCLIENTE}/contatos', [ContactController::class, "store"]);
    Route::patch('clientes/{IDCLIENTE}/contatos/{IDCONTATO}', [ContactController::class, "update"]);
    Route::delete('clientes/{IDCLIENTE}/contatos/{IDCONTATO}', [ContactController::class, "delete"]);

    // Coligadas 
    Route::get('coligadas', [ColigadaController::class, "index"]);
    Route::get('clientes/{IDCLIENTE}/coligadas/{IDCOLIGADA}', [ColigadaController::class, "indexId"]);
    Route::get('clientes/{IDCLIENTE}/coligadas', [ColigadaController::class, "indexClient"]);
    Route::post('clientes/{IDCLIENTE}/coligadas', [ColigadaController::class, "store"]);
    Route::patch('clientes/{IDCLIENTE}/coligadas/{IDCOLIGADA}', [ColigadaController::class, "update"]);
    Route::delete('clientes/{IDCLIENTE}/coligadas/{IDCOLIGADA}', [ColigadaController::class, "delete"]);

    // Licencas coligadas 
    Route::get('licencas', [ColigLicenseController::class, "index"]);
    Route::get('clientes/{IDCLIENTE}/coligadas/{IDCOLIGADA}/licencas/{IDPRODUTO}', [ColigLicenseController::class, "indexId"]);
    Route::get('clientes/{IDCLIENTE}/licencas', [ColigLicenseController::class, "indexClient"]);
    Route::get('clientes/{IDCLIENTE}/coligadas/{IDCOLIGADA}/licencas', [ColigLicenseController::class, "indexColigada"]);
    Route::post('clientes/{IDCLIENTE}/coligadas/{IDCOLIGADA}/licencas', [ColigLicenseController::class, "store"]);
    Route::patch('clientes/{IDCLIENTE}/coligadas/{IDCOLIGADA}/licencas/{IDPRODUTO}', [ColigLicenseController::class, "update"]);
    Route::delete('clientes/{IDCLIENTE}/coligadas/{IDCOLIGADA}/licencas/{IDPRODUTO}', [ColigLicenseController::class, "delete"]);

    // Produtos 
    Route::get('Zwnproduto', [ProductController::class, "index"]);
    Route::get('Zwnproduto/{IDPRODUTO}', [ProductController::class, "indexId"]);
    Route::post('Zwnproduto', [ProductController::class, "store"]);
    Route::patch('Zwnproduto/{IDPRODUTO}', [ProductController::class, "update"]);
    Route::delete('Zwnproduto/{IDPRODUTO}', [ProductController::class, "delete"]);

    // Usuarios Empresa 
    Route::get('Zwnusuempresas', [UserCompanyControllerAPI::class, "index"]);
    Route::get('Zwnusuempresas{IDUSUARIOEMPRESA}', [UserCompanyControllerAPI::class, "indexId"]);
    Route::post('Zwnusuempresas', [UserCompanyControllerAPI::class, "store"]);
    Route::put('Zwnusuempresas/{IDUSUARIOEMPRESA}', [UserCompanyControllerAPI::class, "update"]);
    Route::delete('Zwnusuempresas/{IDUSUARIOEMPRESA}', [UserCompanyControllerAPI::class, "delete"]);

});




