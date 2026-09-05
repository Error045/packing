<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TiposRecepciones extends Model
{
    protected $table = 'tipos_recepciones';

    protected $fillable = [
        'estado',
        'descripcion',
        'tipo',
        'tipos_operaciones_id'
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    public function tipoOperacion(): BelongsTo
    {
        return $this->belongsTo(TiposOperaciones::class, 'tipos_operaciones_id');
    }
}
