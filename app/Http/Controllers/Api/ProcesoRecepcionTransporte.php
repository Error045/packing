<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ProcesoRecepcionTransporte extends Controller
{
    
    

   //**********************************************************************************/
    // ******************VALIDA CÓDIGO PROCESO TRANSPORTE RECEPCIÓN ****************************************/     
    /**
     * GET /api/validar/{codigo}
     * Valida si el bin leído por código de barras pertenece al proceso activo
     */
    public function validarCodigoTransRec($codigo)
    {
        $detalleBin = DB::table('contenedores as a')
            ->join('recepciones as b', 'a.recepciones_id', '=', 'b.id')
            ->join('procesos as c', 'b.id', '=', 'c.recepciones_id')
            ->join('productos as d', 'a.productos_id', '=', 'd.id')
            ->join('variedades as e', 'a.variedades_id', '=', 'e.id')
            ->join('calibres as f', 'a.calibres_id', '=', 'f.id')
            ->where('c.estados_procesos_id', 1)
            ->where('a.estados_contenedores_id', 2) // Solo en estados_contenedores_id = 2 'En Proceso'
            ->where('a.tipos_ubicaciones_id', 1)  // tipos_ubicaciones_id = 1 'En patio Proceso'
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
    // **************************PROCESA CONTENEDOR****************** */
    public function procesarContenedorTransRec($id)
    {
        try {
            DB::beginTransaction();

            // 1. Obtenemos el registro completo del contenedor
            $contenedor = DB::table('contenedores')->where('id', $id)->first();

            // Verificamos que exista y que esté en ubicación 2 (Patio recepción)
            if (!$contenedor || $contenedor->tipos_ubicaciones_id != 1) {
                DB::rollBack();
                return response()->json(['mensaje' => 'El contenedor no existe o ya fue procesado'], 400);
            }

            // 2. Actualizamos la ubicación del contenedor a 2 ( Patio Proceso)
            DB::table('contenedores')
                ->where('id', $id)
                ->update(['tipos_ubicaciones_id' => 2]);

            // 3. Registramos en el historial usando los datos exactos del esquema
            DB::table('contenedores_historial')->insert([
                // 🔴 NOTA:Se  asigna '3' asumiendo que es el ID de movimiento para "traslado". Ajusta esto a tu tabla tipos_movimientos.
                'tipos_movimientos_id' => 3,
                'tipos_contenedores_id' => $contenedor->tipos_contenedores_id,
                'contenedores_id' => $id,
                'tipos_ubicaciones_id' => 2,  // Ubicación cambia a 2 'Patio Proceso' 
                'estados_contenedores_id' => $contenedor->estados_contenedores_id, // El nuevo estado
                'kilos_brutos' => $contenedor->kilos_brutos,
                'kilos_netos' => $contenedor->kilos_netos,
                // 🔴 NOTA: Aquí asumo que envías el user_id o usas el de registro. Temporalmente en 1.
                'users_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return response()->json(['mensaje' => '✅ Bin procesado y registrado en historial con éxito'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['mensaje' => 'Error en servidor', 'error' => $e->getMessage()], 500);
        }
    }


    //**************************************************************************************************************
    // ****************************OBTENER BINES PROCESO ACTUAL GRUA */


    public function obtenerBinesProcesoActualTransRec()
    {
        try {
            $bines = DB::table('contenedores as a')
                ->join('recepciones as b', 'a.recepciones_id', '=', 'b.id')
                ->join('procesos as c', 'b.id', '=', 'c.recepciones_id')
                ->join('productos as d', 'a.productos_id', '=', 'd.id')
                ->join('variedades as e', 'a.variedades_id', '=', 'e.id')
                ->join('calibres as f', 'a.calibres_id', '=', 'f.id')
                ->join('tipos_contenedores as g', 'a.tipos_contenedores_id', '=', 'g.id')
                ->join('tipos_ubicaciones as h', 'a.tipos_ubicaciones_id', '=', 'h.id')
                ->where('c.estados_procesos_id', 1)
                ->whereIn('a.tipos_ubicaciones_id', [1, 2])
                ->whereIn('a.estados_contenedores_id', [1, 2, 3])  // se agregaron los estados para mayor seguridad, pero la ubicacion debería filtrar los bines que corresponden a esa recepción
                ->select(
                    'a.id as contenedor_id',
                    'b.id as recepcion_id',
                    'a.tipos_ubicaciones_id as ubicacion',
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
    //************MOSTRAR RESUMEN TRANSPORTE GRUA */

    public function obtenerTransRecResumenProcesoActual()
    {
        $resumen = DB::table('contenedores as a')
            ->join('recepciones as b', 'a.recepciones_id', '=', 'b.id')
            ->join('procesos as c', 'b.id', '=', 'c.recepciones_id')
            ->join('tipos_ubicaciones as d', 'a.tipos_ubicaciones_id', '=', 'd.id')
            ->where('c.estados_procesos_id', 1)
            ->whereIn('a.tipos_ubicaciones_id', [1, 2])
            ->whereIn('a.estados_contenedores_id', [1, 2, 3])   // grua valida por las ubicaciones
            ->selectRaw('
                COUNT(a.id) as total_bines,
                COALESCE(SUM(CASE WHEN a.tipos_ubicaciones_id = 2 THEN 1 ELSE 0 END), 0) as procesados,
                COALESCE(SUM(CASE WHEN a.tipos_ubicaciones_id = 1 THEN 1 ELSE 0 END), 0) as restantes,
                COALESCE(SUM(a.kilos_netos), 0) as kilos_totales
            ')
            ->first();

        return response()->json($resumen, 200);
    }


    //************************************************************************************************************************************************************************************* */  
    //************MOSTRAR PROCESO PALLET **
}
