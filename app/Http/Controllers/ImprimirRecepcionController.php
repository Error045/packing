<?php

namespace App\Http\Controllers;

use App\Models\Recepcion;
use Illuminate\Support\Facades\DB;

class ImprimirRecepcionController extends Controller
{
    public function __invoke(Recepcion $recepcion)
    {
        $recepcion->load('persona');

        $contenedores = DB::table('contenedores as a')
            ->join('contenedores_historial as g', 'a.id', '=', 'g.contenedores_id')
            ->leftJoin('productos as c', 'a.productos_id', '=', 'c.id')
            ->leftJoin('variedades as d', 'a.variedades_id', '=', 'd.id')
            ->leftJoin('calibres as e', 'a.calibres_id', '=', 'e.id')
            ->select(
                'a.id as contenedor_id',
                'c.nombre as producto',
                'd.nombre as variedad',
                'e.nombre as calibre',
                'g.kilos_netos'
            )
            ->where('a.recepciones_id', $recepcion->id)
            ->where('g.estados_contenedores_id', 1)
            ->get();

        return view('filament.resources.recepcions.imprimir', compact('recepcion', 'contenedores'));
    }
}