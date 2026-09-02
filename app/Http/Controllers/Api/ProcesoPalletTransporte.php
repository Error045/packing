<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProcesoPalletTransporte extends Controller
{
    

    
 //**********************************************************************************/
    // ******************VALIDA CÓDIGO PROCESO TRANSPORTE PALLET ****************************************/     
    /**
     * GET /api/validar/{codigo}
     * Valida si el bin leído por código de barras pertenece al proceso activo
     */
    public function validarCodigoPallTrans($codigo)
    {
        $detalleBin = DB::table('contenedores as a')
            ->join('procesos_bines_origen as b', 'a.id', '=', 'b.contenedores_id')
            ->join('procesos_paletizado as c', 'b.procesos_paletizado_id', '=', 'c.id')
            ->join('productos as d', 'a.productos_id', '=', 'd.id')
            ->join('variedades as e', 'a.variedades_id', '=', 'e.id')
            ->join('calibres as f', 'a.calibres_id', '=', 'f.id')
            ->where('c.estados_procesos_pallets_id', 1)
            ->where('b.estados_bines_id', 1) // Solo en estados_bines_id = 1 'En Espera', estados_bines_id es parte de la tabla procesos_bines_origen y lo que define es los 3 estados que está el bin, 1 ='En espera', 2='transportado', 3 = 'Procesado' 
            // ->where('a.tipos_ubicaciones_id', 1)  // comentado por que el bin puede estar en cualquier parte del proceso, para dar un poco de flexibilidad a la lectura del código, pero la mayor parte de las veces de un proceso normal es en las camaras de frío 
            ->where('a.id', $codigo)
            ->select(
                'a.id as contenedor_id',
                'a.recepciones_id as recepcion_id',
                'a.productos_id',
                'd.nombre as Producto',
                'a.variedades_id',
                'e.nombre as Variedad',
                'a.calibres_id',
                'f.nombre as Calibre',
                'a.kilos_brutos',
                'a.kilos_netos'
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
    // **************************PROCESA CONTENEDOR TRANSPORTE PALLET****************** */
    public function procesarContenedorPallTrans($id)
    {
        try {
            DB::beginTransaction();

            // 1. Obtenemos el registro completo del contenedor (a.*) y el estado del bin (b.estados_bines_id)
            $contenedor = DB::table('contenedores as a')
                ->join('procesos_bines_origen as b', 'a.id', '=', 'b.contenedores_id')
                ->where('a.id', $id)
                ->select('a.*', 'b.estados_bines_id') // El select va ANTES del first()
                ->first();

            // Verificamos que exista y que el estado del bin origen sea 1
            if (!$contenedor || $contenedor->estados_bines_id != 1) {
                DB::rollBack();
                return response()->json(['mensaje' => 'El contenedor no existe o ya fue procesado'], 400);
            }

            // 2. Actualizamos la ubicación del contenedor a 4 (Patio Pesaje) y estado a 16 (Repesaje)
            // IMPORTANTE: Se hace en un solo array, no se pueden encadenar dos update()
            DB::table('contenedores')
                ->where('id', $id)
                ->update([
                    'tipos_ubicaciones_id' => 4,
                    'estados_contenedores_id' => 16
                ]);

            // 3. Registramos en el historial usando los datos exactos del esquema
            DB::table('contenedores_historial')->insert([
                'tipos_movimientos_id' => 3, // 3 = ID de movimiento para "traslado"
                'tipos_contenedores_id' => $contenedor->tipos_contenedores_id,
                'contenedores_id' => $id,
                'tipos_ubicaciones_id' => 4,  // Ubicación cambia a 4 'Patio Pesaje' 
                'estados_contenedores_id' => 16, // El nuevo estado Repesaje
                'kilos_brutos' => $contenedor->kilos_brutos,
                'kilos_netos' => $contenedor->kilos_netos,
                'users_id' => 1, // 🔴 NOTA: Asumiendo user 1 temporalmente
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 4. Actualizamos el estado en procesos_bines_origen
            DB::table('procesos_bines_origen')
                ->where('contenedores_id', $id)
                ->update(['estados_bines_id' => 2]); // cambia de estado a 2 'Transportado'

            DB::commit();

            return response()->json(['mensaje' => '✅ Bin procesado y registrado en historial con éxito'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['mensaje' => 'Error en servidor', 'error' => $e->getMessage()], 500);
        }
    }


    //**************************************************************************************************************
    // ****************************OBTENER BINES PROCESO ACTUAL PALLET TRANSPORTE */

    public function obtenerBinesProcesoActualPalletTrans()
    {
        try {
            $bines = DB::table('contenedores as a')
                ->join('procesos_bines_origen as b', 'a.id', '=', 'b.contenedores_id')
                ->join('procesos_paletizado as c', 'b.procesos_paletizado_id', '=', 'c.id')
                ->join('productos as d', 'a.productos_id', '=', 'd.id')
                ->join('variedades as e', 'a.variedades_id', '=', 'e.id')
                ->join('calibres as f', 'a.calibres_id', '=', 'f.id')
                ->join('tipos_contenedores as g', 'a.tipos_contenedores_id', '=', 'g.id')
                ->join('tipos_ubicaciones as h', 'a.tipos_ubicaciones_id', '=', 'h.id')
                ->where('c.estados_procesos_pallets_id', 1)
                //->where('b.estados_bines_id')
                // ->whereIn('a.estados_contenedores_id', [2, 6, 8]) // borrar, cambiar por filtro por tipos_ubicaciones_id y usar funciones_id = 5
                ->select(
                    'a.id as contenedor_id',
                    'a.recepciones_id as recepcion_id',
                    'a.estados_contenedores_id as estado',
                    'a.tipos_ubicaciones_id as ubicacion',
                    'h.nombre as nombre_ubicacion',
                    'a.productos_id',
                    'd.nombre as Producto',
                    'a.variedades_id',
                    'e.nombre as Variedad',
                    'a.calibres_id',
                    'f.nombre as Calibre',
                    'g.nombre as tipo',
                    'a.kilos_brutos as brutos',
                    'a.kilos_netos'
                )

                ->get();

            // Devolvemos siempre 200 OK. Si no hay bines, $bines será []
            return response()->json([
                'mensaje' => $bines->isEmpty() ? 'No hay bines en el proceso actual' : 'Éxito',
                'datos'   => $bines
            ], 200);
        } catch (\Exception $e) {
            // Si hay un error de SQL (ej. columna inexistente), devolvemos el error exacto
            return response()->json([
                'mensaje' => 'Error SQL: ' . $e->getMessage(),
                'datos'   => []
            ], 500);
        }
    }


    //*************************************************************************************************************** */  
    //************MOSTRAR RESUMEN PROCESO PALLET */

    public function obtenerResumenProcesoActualPalletTrans()
    {
        $resumen = DB::table('contenedores as a')
            ->join('procesos_bines_origen as b', 'a.id', '=', 'b.contenedores_id')
            ->join('procesos_paletizado as c', 'b.procesos_paletizado_id', '=', 'c.id')
            ->where('c.estados_procesos_pallets_id', 1)
            // ->whereIn('a.estados_bines_id', [1,2,3])
            ->selectRaw('
                COUNT(a.id) as total_bines,
                COALESCE(SUM(CASE WHEN b.estados_bines_id <> 1 THEN 1 ELSE 0 END), 0) as procesados,    
                COALESCE(SUM(CASE WHEN b.estados_bines_id = 1 THEN 1 ELSE 0 END), 0) as restantes,     
                COALESCE(SUM(a.kilos_netos), 0) as kilos_totales
            ')
            ->first();

        return response()->json($resumen, 200);
    }

//************************************************************************************************************************************************************************************* */  
//************ TRANSPORTE DESPACHO PALLET ******************************************************************************************************************************* */
    /*********************************************************************************************************************************************************************** */
    /*********************************************************************************************************************************************************************************
     * **********************************************************************************************************************************************************************************
     * *********************************************************************************************************************************************************************************
     */
}
