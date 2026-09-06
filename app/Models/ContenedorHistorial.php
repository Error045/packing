<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContenedorHistorial extends Model
{
   protected $table = 'contenedores_historial';

    public function contenedor()
    {
        return $this->belongsTo(Contenedor::class, 'contenedores_id');
    }
}
