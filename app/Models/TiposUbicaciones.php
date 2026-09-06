<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TiposUbicaciones extends Model
{
    protected $table = 'tipos_ubicaciones';

    protected $fillable = [
        'nombre',
        'funciones_id',
        'capacidad',
        'descripcion',
        'estado',
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];
}
