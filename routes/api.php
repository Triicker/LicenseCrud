<?php

use App\Http\Controllers\CompanyControllerAPI;
use App\Http\Controllers\UserControllerAPI;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\UserCompanyControllerAPI;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('auth/login', [AuthController::class, 'login']);
Route::post('auth/logout', [AuthController::class, 'logout']);
Route::post('auth/refresh', [AuthController::class, 'refresh']);

Route::middleware(['apiJWT'])->group(function () {

    Route::get('auth/me', [AuthController::class, 'me']);
    Route::get('Zwnusuarios', [UserControllerAPI::class, "index"]);
    Route::post('Zwnusuarios', [UserControllerAPI::class, "store"]);
    Route::put('Zwnusuarios/{IDUSUARIO}', [UserControllerAPI::class, "update"]);
    Route::delete('Zwnusuarios/{IDUSUARIO}', [UserControllerAPI::class, "delete"]);

    Route::get('Zwnempresas', [CompanyControllerAPI::class, "index"]);
    Route::post('Zwnempresas', [CompanyControllerAPI::class, "store"]);
    Route::put('Zwnempresas/{IDEMPRESA}', [CompanyControllerAPI::class, "update"]);
    Route::delete('Zwnempresas/{IDEMPRESA}', [CompanyControllerAPI::class, "delete"]);

    Route::get('Zwnclientes/{IDEMPRESA}', [ClientController::class, 'show']);
    Route::get('Zwnclientes', [ClientController::class, "index"]);
    Route::post('Zwnclientes', [ClientController::class, "store"]);
    Route::put('Zwnclientes/{IDCLIENTE}', [ClientController::class, "update"]);
    Route::delete('Zwnclientes/{IDCLIENTE}', [ClientController::class, "delete"]);

    Route::get('Zwnusuempresas', [UserCompanyControllerAPI::class, "index"]);
    Route::post('Zwnusuempresas', [UserCompanyControllerAPI::class, "store"]);
    Route::put('Zwnusuempresas/{IDUSUARIO}', [UserCompanyControllerAPI::class, "update"]);
    Route::delete('Zwnusuempresas/{IDUSUARIO}', [UserCompanyControllerAPI::class, "delete"]);

    Route::get('Zwnclicontato', [ContactController::class, "index"]);
    Route::post('Zwnclicontato', [ContactController::class, "store"]);
    Route::put('Zwnclicontato/{IDCONTATO}', [ContactController::class, "update"]);
    Route::delete('Zwnclicontato/{IDCONTATO}', [ContactController::class, "delete"]);

});




