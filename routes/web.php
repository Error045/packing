<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImprimirRecepcionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/recepciones/{recepcion}/imprimir', ImprimirRecepcionController::class)
    ->name('recepciones.imprimir')
    ->middleware(['auth']);
