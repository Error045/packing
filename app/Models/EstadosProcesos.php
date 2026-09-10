<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadosProcesos extends Model
{
    protected $table = 'estados_procesos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];
}
