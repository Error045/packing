<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TiposOperaciones extends Model
{
    protected $table = 'tipos_operaciones';

    public $timestamps = false;

    protected $fillable = [
        'estado',
        'nombre',
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];
}
