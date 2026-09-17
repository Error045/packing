<?php

use App\Http\Controllers\ImprimirCalibradoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImprimirRecepcionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/recepciones/{recepcion}/imprimir', ImprimirRecepcionController::class)
    ->name('recepciones.imprimir')
    ->middleware(['auth']);

Route::get('/recepciones/{recepcion}/calibrado/imprimir', ImprimirCalibradoController::class)
    ->name('recepciones.calibrado.imprimir')
    ->middleware(['auth']);
