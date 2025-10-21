<?php

use App\Http\Controllers\AccountsController;
use App\Http\Controllers\LinesController;
use App\Http\Controllers\SubscribersController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['message' => 'Hello world!']);
});


Route::group(['prefix' => 'subscriber'], function () {

    Route::post('/', [SubscribersController::class, 'save']); //altaCliente
    Route::put('/{id}', [SubscribersController::class, 'store'])->where(['id' => '[0-9]+']); //modificaCliente
    Route::post('/{id}/baja', [SubscribersController::class, 'destroy'])->where(['id' => '[0-9]+']); //bajaCliente

    Route::post('/account', [AccountsController::class, 'save']);//altaCuenta
    Route::put('/account', [AccountsController::class, 'store']);//modificaNombreCuenta
    Route::post('/account/baja', [AccountsController::class, 'destroy']);//bajaCuenta

    Route::post('/lines', [LinesController::class, 'save']);//altaLinea
    Route::put('/lines/fechaAlta', [LinesController::class, 'store']);//modificaFechaAltaLinea
    Route::put('/lines/fechaBaja', [LinesController::class, 'destroy']);//modificaFechaBajaLinea
});
