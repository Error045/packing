<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Variedad extends Model
{
    protected $table = 'variedades';

    protected $fillable = [
        'descripcion',
        'estado',
        'nombre',
        'producto_id',
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }
}
