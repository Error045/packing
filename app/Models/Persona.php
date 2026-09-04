<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table = 'personas';

    protected $fillable = [
        'email',
        'empresa',
        'estado',
        'nombre',
        'rut',
        'telefono'
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];
}
