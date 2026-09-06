<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recepcion extends Model
{
     // Indicar a Laravel la tabla exacta en MySQL
    protected $table = 'recepciones';

     protected $fillable = [
        'tipos_recepciones_id',
        
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'personas_id');
    }

    public function contenedores()
    {
        return $this->hasMany(Contenedor::class, 'recepciones_id');
    }

    public function TiposRecepciones()
    {
        return $this->belongsTo(TiposRecepciones::class, 'tipos_recepciones_id');
    }

     public function estadoRecepcion()
    {
        return $this->belongsTo(EstadoRecepcion::class, 'estados_recepciones_id');
    }

    // Clave foránea explícita: recepciones_id en la tabla procesos
    public function proceso()
    {
        return $this->hasOne(Proceso::class, 'recepciones_id');
    }
}
