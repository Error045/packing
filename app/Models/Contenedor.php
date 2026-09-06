<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contenedor extends Model
{
    use HasFactory;

    // Ajusta el nombre de la tabla si en tu BD se llama 'bines' o 'contenedores'
    protected $table = 'contenedores'; 

    // Desactiva timestamps si la tabla no tiene 'created_at' y 'updated_at'
    public $timestamps = true; 

  //  protected $guarded = [];
    protected $fillable = ['estados_contenedores_id'];

    // Relación con Recepción
    public function recepcion()
    {
        return $this->belongsTo(Recepcion::class, 'recepciones_id');
    }

    // Relación con Producto
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'productos_id');
    }

    // Relación con Variedad
    public function variedad()
    {
        return $this->belongsTo(Variedad::class, 'variedades_id');
    }

    // Relación con Calibre
    public function calibre()
    {
        return $this->belongsTo(Calibre::class, 'calibres_id');
    }

    // Relación con Historiales
    public function historiales()
    {
        return $this->hasMany(ContenedorHistorial::class, 'contenedores_id');
    }


}