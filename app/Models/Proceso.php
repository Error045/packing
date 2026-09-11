<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Proceso extends Model
{
    protected $table = 'procesos';

    protected $fillable = [
        'recepciones_id',
        'fecha',
        'hora',
        'descripcion',
        'estados_procesos_id',
        'estado'
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    public function estadosProcesos(): BelongsTo
    {
        return $this->belongsTo(EstadosProcesos::class, 'estados_procesos_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function recepcion(): BelongsTo
    {
        return $this->belongsTo(Recepcion::class, 'recepciones_id');
    }
}
