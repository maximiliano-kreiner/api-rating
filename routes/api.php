<?php

use App\Http\Controllers\AccountsController;
use App\Http\Controllers\LinesController;
use App\Http\Controllers\PlansController;
use App\Http\Controllers\SubscribersController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['message' => 'Hello world!']);
});


Route::group(['prefix' => 'subscriber'], function () {
    Route::post('/', [SubscribersController::class, 'save']); //altaCliente
    Route::put('/', [SubscribersController::class, 'store']); //modificaCliente
    Route::post('/baja', [SubscribersController::class, 'destroy']); //bajaCliente
});

Route::group(['prefix' => 'account'], function () {
    Route::post('/', [AccountsController::class, 'save']); //altaCuenta
    Route::put('/', [AccountsController::class, 'store']); //modificaNombreCuenta
    Route::post('/baja', [AccountsController::class, 'destroy']); //bajaCuenta
});

Route::group(['prefix' => 'lines'], function () {
    Route::post('/', [LinesController::class, 'save']); //altaLinea
    Route::put('/fechaAlta', [LinesController::class, 'store']); //modificaFechaAltaLinea
    Route::put('/fechaBaja', [LinesController::class, 'destroy']); //modificaFechaBajaLinea
});

Route::group(['prefix' => 'plans'], function () {
    Route::get('/', [PlansController::class, 'index']); //altaLinea
});
