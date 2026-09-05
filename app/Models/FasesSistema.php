<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FasesSistema extends Model
{
    protected $table = 'fases_sistema';

    protected $fillable = [
        'estado',
        'nombre',
        'prefijo',
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];
}
