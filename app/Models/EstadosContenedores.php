<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EstadosContenedores extends Model
{
    protected $table = 'estados_contenedores';

    public $timestamps = false;

    protected $fillable = [
        'descripcion',
        'estado',
        'fases_sistema_id',
        'nombre'
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    public function faseSistema(): BelongsTo
    {
        return $this->belongsTo(FasesSistema::class, 'fases_sistema_id');
    }
}
