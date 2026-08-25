<?php

use App\Models\Contenedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProcesoController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// PROCESO RECEPCION 

Route::get('/validar/{codigo}', [ProcesoController::class, 'validarCodigo']);
Route::post('/contenedor/{id}/procesar', [ProcesoController::class, 'procesarContenedor']);
Route::get('/proceso-actual/bines', [ProcesoController::class, 'obtenerBinesProcesoActual']);
Route::get('/proceso-actual/resumen', [ProcesoController::class, 'obtenerResumenProcesoActual']);


// GRUA PROCESO RECEPCION 

Route::get('/validar-proceso-trans-rec/{codigo}', [ProcesoController::class, 'validarCodigoTransRec']);
Route::post('/contenedor-trans-rec/{id}/procesar', [ProcesoController::class, 'procesarContenedorTransRec']);
Route::get('/proceso-trans-rec-actual/bines', [ProcesoController::class, 'obtenerBinesProcesoActualTransRec']);
Route::get('proceso-trans-rec-actual/resumen', [ProcesoController::class, 'obtenerTransRecResumenProcesoActual']);

// PROCESO PALETIZACIÓN 

Route::get('/validar-pallet/{codigo}', [ProcesoController::class, 'validarCodigoPall']);
Route::post('/contenedor-pallet/{id}/procesar', [ProcesoController::class, 'procesarContenedorPall']);
Route::get('//proceso-pallet-actual/bines', [ProcesoController::class, 'obtenerBinesProcesoActualPallet']);
Route::get('/proceso-pallet-actual/resumen', [ProcesoController::class, 'obtenerResumenProcesoActualPallet']);

// PROCESO PALETIZACIÓN GRUA

Route::get('/validar-proceso-trans-pall/{codigo}', [ProcesoController::class, 'validarCodigoPallTrans']);
Route::post('/contenedor-trans-pall/{id}/procesar', [ProcesoController::class, 'procesarContenedorPallTrans']);
Route::get('/proceso-trans-pall-actual/bines', [ProcesoController::class, 'obtenerBinesProcesoActualPalletTrans']);
Route::get('/proceso-trans-pall-actual/resumen', [ProcesoController::class, 'obtenerResumenProcesoActualPalletTrans']);

// PROCESO DESPACHO PALLET GRUA

use App\Http\Controllers\PalletController;

// La ruta que consume tu app en Flutter

Route::get('/validar-despacho-pallet/{codigo}', [ProcesoController::class, 'validarCodigoDespacho']);
Route::post('/contenedor-despacho-pallet/{id}/procesar', [ProcesoController::class, 'procesarContenedorDespachoPallet']);
Route::get('/proceso-despacho-pallet-actual/pallets', [ProcesoController::class, 'obtenerPalletsTrans']);
Route::get('/proceso-despacho-pallet-actual/resumen', [ProcesoController::class, 'obtenerResumenProcesoActualPalletDespacho']);
