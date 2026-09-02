<?php

use App\Http\Controllers\Api\DespachoPallet;
use App\Http\Controllers\Api\ProcesoPallet;
use App\Http\Controllers\Api\ProcesoPalletTransporte;
use App\Http\Controllers\Api\ProcesoRecepcion;
use App\Http\Controllers\Api\ProcesoRecepcionTransporte;
use App\Models\Contenedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
//use App\Http\Controllers\Api\;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// PROCESO RECEPCION 

Route::get('/validar/{codigo}', [ProcesoRecepcion::class, 'validarCodigo']);
Route::post('/contenedor/{id}/procesar', [ProcesoRecepcion::class, 'procesarContenedor']);
Route::get('/proceso-actual/bines', [ProcesoRecepcion::class, 'obtenerBinesProcesoActual']);
Route::get('/proceso-actual/resumen', [ProcesoRecepcion::class, 'obtenerResumenProcesoActual']);


// GRUA PROCESO RECEPCION 

Route::get('/validar-proceso-trans-rec/{codigo}', [ProcesoRecepcionTransporte::class, 'validarCodigoTransRec']);
Route::post('/contenedor-trans-rec/{id}/procesar', [ProcesoRecepcionTransporte::class, 'procesarContenedorTransRec']);
Route::get('/proceso-trans-rec-actual/bines', [ProcesoRecepcionTransporte::class, 'obtenerBinesProcesoActualTransRec']);
Route::get('proceso-trans-rec-actual/resumen', [ProcesoRecepcionTransporte::class, 'obtenerTransRecResumenProcesoActual']);

// PROCESO PALETIZACIÓN 

Route::get('/validar-pallet/{codigo}', [ProcesoPallet::class, 'validarCodigoPall']);
Route::post('/contenedor-pallet/{id}/procesar', [ProcesoPallet::class, 'procesarContenedorPall']);
Route::get('//proceso-pallet-actual/bines', [ProcesoPallet::class, 'obtenerBinesProcesoActualPallet']);
Route::get('/proceso-pallet-actual/resumen', [ProcesoPallet::class, 'obtenerResumenProcesoActualPallet']);

// PROCESO PALETIZACIÓN GRUA

Route::get('/validar-proceso-trans-pall/{codigo}', [ProcesoPalletTransporte::class, 'validarCodigoPallTrans']);
Route::post('/contenedor-trans-pall/{id}/procesar', [ProcesoPalletTransporte::class, 'procesarContenedorPallTrans']);
Route::get('/proceso-trans-pall-actual/bines', [ProcesoPalletTransporte::class, 'obtenerBinesProcesoActualPalletTrans']);
Route::get('/proceso-trans-pall-actual/resumen', [ProcesoPalletTransporte::class, 'obtenerResumenProcesoActualPalletTrans']);

// PROCESO DESPACHO PALLET GRUA

use App\Http\Controllers\PalletController;

// La ruta que consume tu app en Flutter

Route::get('/validar-despacho-pallet/{codigo}', [DespachoPallet::class, 'validarCodigoDespacho']);
Route::post('/contenedor-despacho-pallet/{id}/procesar', [DespachoPallet::class, 'procesarContenedorDespachoPallet']);
Route::get('/proceso-despacho-pallet-actual/pallets', [DespachoPallet::class, 'obtenerPalletsTrans']);
Route::get('/proceso-despacho-pallet-actual/resumen', [DespachoPallet::class, 'obtenerResumenProcesoActualPalletDespacho']);
