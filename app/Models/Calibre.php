<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Calibre extends Model
{
    protected $table = 'calibres';

    protected $fillable = [
        'nombre',
        'numero',
        'descripcion',
        'variedades_id',
        'estados_calibres_id',
        'estado'
    ];

    public function variedades(): BelongsTo
    {
        return $this->belongsTo(Variedad::class, 'variedades_id');
    }

    protected $casts = [
        'estado' => 'boolean',
    ];
}
