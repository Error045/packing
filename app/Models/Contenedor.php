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
}