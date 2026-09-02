<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DespachoPallet extends Controller
{
    
 //**********************************************************************************/
    // ******************VALIDA CÓDIGO PROCESO TRANSPORT DESPACHO ****************************************/     
    /**
     * GET /api/validar/{codigo}
     * Valida si el bin leído por código de barras pertenece al proceso activo
     */
    public function validarCodigoDespacho($codigo)
    {
        $detalleBin = DB::table('pallets as a')
            ->join('despachos_pallets as b', 'a.id', '=', 'b.pallets_id')
            ->join('despachos as c', 'b.despachos_id', '=', 'c.id')
            ->where('c.estados_despachos_id', 1)
            ->where('b.estados_despachos_pallets_id', 1)
            ->where('a.id', $codigo)
            ->select(
                'a.id as contenedor_id'

            )
            ->first();

        if ($detalleBin) {
            return response()->json([
                'valido' => true,
                'datos' => $detalleBin, // 🟢 Ahora enviamos el objeto completo
                'mensaje' => '✅ Bin encontrado',
            ], 200);
        }

        return response()->json([
            'valido' => false,
            'mensaje' => 'Error 404: Bin no encontrado o no disponible',
        ], 404);
    }


    //******************************************************************************************************************
    // **************************PROCESA  TRANSPORTE PALLET****************** */
    public function procesarContenedorDespachoPallet($id)
    {
        try {
            DB::beginTransaction();

            // 1. Obtenemos el registro completo del contenedor (a.*) y el estado del bin (b.estados_bines_id)
            $contenedor = DB::table('pallets as a')
                ->join('despachos_pallets as b', 'a.id', '=', 'b.pallets_id')
                ->join('despachos as c', 'b.despachos_id', '=', 'c.id')
                ->where('a.id', $id)
                ->select('a.*', 'b.estados_despachos_pallets_id') // El select va ANTES del first()
                ->first();

            // Verificamos que exista y que el estado del bin origen sea 1
            if (!$contenedor || $contenedor->estados_despachos_pallets_id != 1) {
                DB::rollBack();
                return response()->json(['mensaje' => 'El contenedor no existe o ya fue procesado'], 400);
            }

            /* 2. Actualizamos la ubicación del contenedor a 4 (Patio Pesaje) y estado a 16 (Repesaje)
            // IMPORTANTE: Se hace en un solo array, no se pueden encadenar dos update()
            DB::table('pallets')
                ->where('id', $id)
                ->update([
                    'tipos_ubicaciones_id' => 13,
                    'estados_contenedores_id' => 17
                ]);

            // 3. Registramos en el historial usando los datos exactos del esquema
            DB::table('pallets_historial')->insert([
                'tipos_movimientos_id' => 15, // Salida de packing
                'tipos_contenedores_id' => $contenedor->tipos_contenedores_id,
                'pallets_id' => $id,
                'tipos_ubicaciones_id' => 13,  // Ubicación cambia a 13 'Sale del Packing' 
                'estados_contenedores_id' => 17, // Sale del Packing
                'kilos_brutos' => $contenedor->kilos_brutos,
                'kilos_netos' => $contenedor->kilos_netos,
                'users_id' => 1, // 🔴 NOTA: Asumiendo user 1 temporalmente
                'created_at' => now(),
                'updated_at' => now(),
            ]); */

            // 4. Actualizamos el estado en procesos_bines_origen
            DB::table('despachos_pallets')
                ->where('pallets_id', $id)
                ->update(['estados_despachos_pallets_id' => 2]); // cambia de estado a 2 'Transportado'

            DB::commit();

            return response()->json(['mensaje' => '✅ Bin procesado y registrado en historial con éxito'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['mensaje' => 'Error en servidor', 'error' => $e->getMessage()], 500);
        }
    }




    //**************************************************************************************************************
    // ****************************OBTENER BINES PROCESO ACTUAL PALLET TRANSPORTE */



    public function obtenerPalletsTrans()
    {
        try {
            $datos = DB::table('cajas as c')
                ->join('pallets as b', 'c.pallet_id', '=', 'b.id')
                ->join('despachos_pallets as dp', 'b.id', '=', 'dp.pallets_id')
                ->join('despachos as de', 'dp.despachos_id', '=', 'de.id')
                ->join('tipos_ubicaciones as t', 'c.pallet_id', '=', 't.id')
                ->join('productos as p', 'c.productos_id', '=', 'p.id')
                ->join('variedades as v', 'c.variedades_id', '=', 'v.id')
                ->join('calibres as cal', 'c.calibres_id', '=', 'cal.id')
                ->where('de.estados_despachos_id', 1)
                ->select(
                    'c.pallet_id',
                    DB::raw('COUNT(c.pallet_id) as cant_cajas'),
                    'b.tipos_ubicaciones_id as ubicacion',
                    'dp.estados_despachos_pallets_id as estado_despachos',
                    'p.nombre as producto',
                    'v.nombre as variedad',
                    'cal.nombre as calibre'

                )
                // Debes agrupar por todos los campos que no sean funciones de agregación (como COUNT)
                ->groupBy(
                    'c.pallet_id',
                    'b.tipos_ubicaciones_id',
                    'dp.estados_despachos_pallets_id',
                    'p.nombre',
                    'v.nombre',
                    'cal.nombre'
                )
                ->orderBy('c.pallet_id', 'ASC')
                ->orderBy('calibres_id', 'ASC')
                ->get();

            return response()->json([
                'mensaje' => 'Pallets obtenidos correctamente',
                'datos' => $datos
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error en el servidor',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    //*************************************************************************************************************** */  
    //************MOSTRAR RESUMEN PROCESO PALLET */

    public function obtenerResumenProcesoActualPalletDespacho()
    {
        $resumen = DB::table('pallets as a')
            ->join('despachos_pallets as b', 'a.id', '=', 'b.pallets_id')
            ->join('despachos as c', 'b.despachos_id', '=', 'c.id')
            ->where('c.estados_despachos_id', 1)
            ->selectRaw('
                COUNT(a.id) as total_bines,
                COALESCE(SUM(CASE WHEN b.estados_despachos_pallets_id <> 1 THEN 1 ELSE 0 END), 0) as procesados,    
                COALESCE(SUM(CASE WHEN b.estados_despachos_pallets_id = 1 THEN 1 ELSE 0 END), 0) as restantes,     
                COALESCE(SUM(a.kilos_netos), 0) as kilos_totales
            ')
            ->first();

        return response()->json($resumen, 200);
    }
}
