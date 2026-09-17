<?php

namespace App\Http\Controllers;

use App\Models\Recepcion;
use App\Models\Contenedor;
use Illuminate\Support\Facades\DB;

class ImprimirCalibradoController extends Controller
{
    public function __invoke(Recepcion $recepcion)
    {
        $recepcion->load(['persona', 'TiposRecepciones', 'proceso']);

        $totalKilosGeneral = (float) DB::table('contenedores as a')
            ->join('contenedores_historial as g', 'a.id', '=', 'g.contenedores_id')
            ->where('a.recepciones_id', $recepcion->id)
            ->where('g.estados_contenedores_id', 5)
            ->sum('g.kilos_netos');

        $calibres = Contenedor::query()
            ->join('contenedores_historial as g', 'contenedores.id', '=', 'g.contenedores_id')
            ->join('calibres as e', 'contenedores.calibres_id', '=', 'e.id')
            ->select([
                DB::raw('MIN(contenedores.id) as id'),
                'e.nombre as calibre',
                DB::raw('SUM(g.kilos_netos) as netos'),
            ])
            ->where('contenedores.recepciones_id', $recepcion->id)
            ->where('g.estados_contenedores_id', 5)
            ->groupBy('e.id', 'e.nombre')
            ->orderBy('e.id')
            ->get()
            ->map(function ($row) use ($totalKilosGeneral) {
                $row->porcentaje = $totalKilosGeneral > 0
                    ? ($row->netos / $totalKilosGeneral) * 100
                    : 0;
                return $row;
            });

        return view('filament.resources.recepcions.imprimir-calibrado', compact('recepcion', 'calibres', 'totalKilosGeneral'));
    }
}
