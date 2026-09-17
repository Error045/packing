<?php

namespace App\Http\Controllers;

use App\Models\Recepcion;
use App\Models\Contenedor;
use Illuminate\Support\Facades\DB;

class ImprimirValorizacionController extends Controller
{
    public function __invoke(Recepcion $recepcion)
    {
        $recepcion->load(['persona', 'TiposRecepciones', 'proceso']);

        $detalle = Contenedor::query()
            ->join('contenedores_historial as g', 'contenedores.id', '=', 'g.contenedores_id')
            ->join('productos as c', 'contenedores.productos_id', '=', 'c.id')
            ->join('variedades as d', 'contenedores.variedades_id', '=', 'd.id')
            ->join('calibres as e', 'contenedores.calibres_id', '=', 'e.id')
            ->join('productos_precios as h', 'contenedores.id', '=', 'h.contenedores_id')
            ->select([
                DB::raw('MIN(contenedores.id) as id'),
                'c.nombre as producto',
                'd.nombre as variedad',
                'e.nombre as calibre',
                'contenedores.calibres_id',
                DB::raw('SUM(g.kilos_netos) as netos'),
                'h.precio',
                DB::raw('SUM(g.kilos_netos * h.precio) as total'),
            ])
            ->where('contenedores.recepciones_id', $recepcion->id)
            ->where('g.estados_contenedores_id', 5)
            ->groupBy('c.nombre', 'd.nombre', 'e.nombre', 'contenedores.calibres_id', 'h.precio')
            ->orderBy('contenedores.calibres_id', 'asc')
            ->get();

        $totalKilos    = $detalle->sum('netos');
        $subtotalNeto  = $detalle->sum('total');
        $iva           = $subtotalNeto * 0.19;
        $totalGeneral  = $subtotalNeto * 1.19;

        return view('filament.resources.recepcions.imprimir-valorizacion', compact(
            'recepcion',
            'detalle',
            'totalKilos',
            'subtotalNeto',
            'iva',
            'totalGeneral'
        ));
    }
}
