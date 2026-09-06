<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Proceso extends Model
{
    protected $table = 'procesos';

    public function recepciones()
    {
        return $this->belongsTo(Recepcion::class, 'recepciones_id');
    }

}
