<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TiposContenedores extends Model
{
    protected $table = 'tipos_contenedores';

    protected $fillable = [
        'capacidad',
        'descripcion',
        'dimensiones',
        'estado',
        'material',
        'nombre',
        'tara',
        'tipos_clases'
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];
}
