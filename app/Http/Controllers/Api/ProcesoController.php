<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ProcesoController extends Controller
{
    /**
     * GET /api/proceso/{proceso_id}/resumen
     * Calcula los totales para el dashboard superior de Flutter
     */


    //**********************************************************************************/
    // ******************VALIDA CÓDIGO PROCESO ****************************************/     
    /**
     * GET /api/validar/{codigo}
     * Valida si el bin leido por código de barras pertenece al proceso activo
     */
   public function validarCodigo($codigo)
    {
        $detalleBin = DB::table('contenedores as a')
            ->join('recepciones as b', 'a.recepciones_id', '=', 'b.id')
            ->join('procesos as c', 'b.id', '=', 'c.recepciones_id')
            ->join('productos as d', 'a.productos_id', '=', 'd.id')
            ->join('variedades as e', 'a.variedades_id', '=', 'e.id')
            ->join('calibres as f', 'a.calibres_id', '=', 'f.id')          
            ->where('c.estados_procesos_id', 1) 
            ->where('a.estados_contenedores_id', 2) // Solo en proceso
            ->where('a.tipos_ubicaciones_id', 2) //   La ubicación debe ser 'patio Proceso', previamente es 1 'patio Recepción', la app flutter debe cambiar este estado a 2,app grúa
            ->where('a.id', $codigo)
            ->select(
                'a.id as contenedor_id',
                'b.id as recepcion_id',
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
  // **************************PROCESA CONTENEDOR RECEPCIÓN****************** */
  public function procesarContenedor($id)
    {
        try {
            DB::beginTransaction();

            // 1. Obtenemos el registro completo del contenedor
            $contenedor = DB::table('contenedores')->where('id', $id)->first();

            // Verificamos que exista y que esté en estado 2 (En proceso)
            if (!$contenedor || $contenedor->estados_contenedores_id != 2) {
                DB::rollBack();
                return response()->json(['mensaje' => 'El contenedor no existe o ya fue procesado'], 400);
            }

            // 2. Actualizamos el estado del contenedor a 3 (Procesado)
            DB::table('contenedores')
                ->where('id', $id)
                ->update(['estados_contenedores_id' => 3]);

            // 3. Registramos en el historial usando los datos exactos del esquema
            DB::table('contenedores_historial')->insert([
                // 🔴 NOTA: Asigné un '2' asumiendo que es el ID de movimiento para "Procesado". Ajusta esto a tu tabla tipos_movimientos.
                'tipos_movimientos_id' => 4, 
                'tipos_contenedores_id' => $contenedor->tipos_contenedores_id,
                'contenedores_id' => $id,
                'tipos_ubicaciones_id' => $contenedor->tipos_ubicaciones_id,
                'estados_contenedores_id' => 3, // El nuevo estado
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
    // ****************************OBTENER BINES PROCESO ACTUAL */
    public function obtenerBinesProcesoActual()
    {
        try {
            $bines = DB::table('contenedores as a')
               ->join('recepciones as b', 'a.recepciones_id', '=', 'b.id')
            ->join('procesos as c', 'b.id', '=', 'c.recepciones_id')
            ->join('productos as d', 'a.productos_id', '=', 'd.id')
            ->join('variedades as e', 'a.variedades_id', '=', 'e.id')
            ->join('calibres as f', 'a.calibres_id', '=', 'f.id')
            ->join('tipos_contenedores as g','a.tipos_contenedores_id', '=', 'g.id')         
            ->where('c.estados_procesos_id', 1) 
            ->whereIn('a.estados_contenedores_id', [2, 3])
            ->select(
                'a.id as contenedor_id',
                'b.id as recepcion_id',
                'a.estados_contenedores_id as estado',
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
//************MOSTRAR RESUMEN PROCESO */

    public function obtenerResumenProcesoActual()
    {
        $resumen = DB::table('contenedores as a')
            ->join('recepciones as b', 'a.recepciones_id', '=', 'b.id')
            ->join('procesos as c', 'b.id', '=', 'c.recepciones_id')
            ->where('c.estados_procesos_id', 1)
            ->whereIn('a.estados_contenedores_id',[2,3])
            ->selectRaw('
                COUNT(a.id) as total_bines,
                COALESCE(SUM(CASE WHEN a.estados_contenedores_id = 3 THEN 1 ELSE 0 END), 0) as procesados,
                COALESCE(SUM(CASE WHEN a.estados_contenedores_id = 2 THEN 1 ELSE 0 END), 0) as restantes,
                COALESCE(SUM(a.kilos_netos), 0) as kilos_totales
            ')
            ->first();

        return response()->json($resumen, 200);
    }


 //************************************************************************************************************************************************************************************* */  
//************MOSTRAR TRANSPORTE PROCESO RECEPCION GRUA ******************************************************************************************************************************* */
/****************RECEPCION GRUA******************************************************************************************************************************************************* */



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
            ->join('tipos_contenedores as g','a.tipos_contenedores_id', '=', 'g.id')
            ->join('tipos_ubicaciones as h','a.tipos_ubicaciones_id', '=', 'h.id')         
            ->where('c.estados_procesos_id', 1)
            ->whereIn('a.tipos_ubicaciones_id', [1,2])
            ->whereIn('a.estados_contenedores_id', [1,2,3])  // se agregaron los estados para mayor seguridad, pero la ubicacion debería filtrar los bines que corresponden a esa recepción
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
            ->whereIn('a.tipos_ubicaciones_id', [1,2])
            ->whereIn('a.estados_contenedores_id',[1,2,3])   // grua valida por las ubicaciones
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
//************MOSTRAR PROCESO PALLET ******************************************************************************************************************************* */
/****************PALETIZADO******************************************************************************************************************************************************* */
/************************************************************************************************************************************************************************************
 * ********************************************************************************************************************************************************************************
 */


    //**********************************************************************************/
    // ******************VALIDA CÓDIGO PROCESO  PALLET ****************************************/     
    /**
     * GET /api/validar/{codigo}
     * Valida si el bin leído por código de barras pertenece al proceso activo
     */
   public function validarCodigoPall($codigo)
    {
        $detalleBin = DB::table('contenedores as a')
            ->join('procesos_bines_origen as b', 'a.id', '=', 'b.contenedores_id')
            ->join('procesos_paletizado as c', 'b.procesos_paletizado_id', '=', 'c.id')
            ->join('productos as d', 'a.productos_id', '=', 'd.id')
            ->join('variedades as e', 'a.variedades_id', '=', 'e.id')
            ->join('calibres as f', 'a.calibres_id', '=', 'f.id')          
            ->where('c.estados_procesos_pallets_id', 1)
            ->where('b.estados_bines_id', 3) // Solo en estados_bines_id = 3 'Transportado', estados_bines_id es parte de la tabla procesos_bines_origen y lo que define es los 3 estados que está el bin, 1 ='En espera', 2='transportado', 3 = 'Repesaje', 4 ='Terminado' 
           // ->where('a.tipos_ubicaciones_id', 1)  // comentado por que el bin puede estar en cualquier parte del proceso, para dar un poco de flexibilidad a la lectura del código, pero la mayor parte de las veces de un proceso normal es en las camaras de frío 
            ->where('a.id', $codigo)
            ->select(
                'a.id as contenedor_id',
                'a.recepciones_id as Origen',
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
  // **************************PROCESA CONTENEDOR  PALLET****************** */

 public function procesarContenedorPall($id)
    {
        try {
            DB::beginTransaction();

            // 1. Obtenemos el registro completo del contenedor (a.*) y el estado del bin (b.estados_bines_id)
            $contenedor = DB::table('contenedores as a')
                              ->join('procesos_bines_origen as b', 'a.id', '=', 'b.contenedores_id') 
                              ->where('a.id', $id)
                              ->select('a.*', 'b.estados_bines_id') // El select va ANTES del first()
                              ->first(); 

            // Verificamos que exista y que el estado del bin origen sea 3 Repesaje
            if (!$contenedor || $contenedor->estados_bines_id != 3) {
                DB::rollBack();
                return response()->json(['mensaje' => 'El contenedor no existe o ya fue procesado'], 400);
            }

            // 2. Actualizamos la ubicación del contenedor a 2 (Patio Proceso) y estado a 8 (Procesado para Pallet)
            // IMPORTANTE: Se hace en un solo array, no se pueden encadenar dos update()
            DB::table('contenedores')
                ->where('id', $id)
                ->update([
                    'tipos_ubicaciones_id' => 2,
                    'estados_contenedores_id' => 8
                ]);

            // 3. Registramos en el historial usando los datos exactos del esquema
            DB::table('contenedores_historial')->insert([
                'tipos_movimientos_id' => 4, // 4 = ID de movimiento para "Termino de Contenedor"
                'tipos_contenedores_id' => $contenedor->tipos_contenedores_id,
                'contenedores_id' => $id,
                'tipos_ubicaciones_id' => 2,  // Ubicación cambia a 2 'Patio Proceso' 
                'estados_contenedores_id' => 8, // 8 = Procesado para Pallet
                'kilos_brutos' => $contenedor->kilos_brutos,
                'kilos_netos' => $contenedor->kilos_netos,
                'users_id' => 1, // 🔴 NOTA: Asumiendo user 1 temporalmente
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 4. Actualizamos el estado en procesos_bines_origen
            DB::table('procesos_bines_origen')
                ->where('contenedores_id', $id)
                ->update(['estados_bines_id' => 4]); // cambia de estado a 4 'Terminado'
                
            DB::commit();

            return response()->json(['mensaje' => '✅ Bin procesado y registrado en historial con éxito'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['mensaje' => 'Error en servidor', 'error' => $e->getMessage()], 500);
        }
    }



    //**************************************************************************************************************
    // ****************************OBTENER BINES PROCESO ACTUAL PALLET */
    public function obtenerBinesProcesoActualPallet()
    {
        try {
            $bines = DB::table('contenedores as a')
            ->join('procesos_bines_origen as b', 'a.id', '=', 'b.contenedores_id')
            ->join('procesos_paletizado as c', 'b.procesos_paletizado_id', '=', 'c.id')
            ->join('productos as d', 'a.productos_id', '=', 'd.id')
            ->join('variedades as e', 'a.variedades_id', '=', 'e.id')
            ->join('calibres as f', 'a.calibres_id', '=', 'f.id')
            ->join('tipos_contenedores as g','a.tipos_contenedores_id', '=', 'g.id')         
            ->where('c.estados_procesos_pallets_id', 1)
           // ->whereIn('a.estados_contenedores_id', [2, 6, 8]) // borrar, cambiar por filtro por tipos_ubicaciones_id y usar funciones_id = 5
            ->select(
                'a.id as contenedor_id',
                'a.recepciones_id as recepcion_id',
                'a.estados_contenedores_id as estado',
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

    public function obtenerResumenProcesoActualPallet()
    {
        $resumen = DB::table('contenedores as a')
            ->join('procesos_bines_origen as b', 'a.id', '=', 'b.contenedores_id')
            ->join('procesos_paletizado as c', 'b.procesos_paletizado_id', '=', 'c.id')
            ->where('c.estados_procesos_pallets_id', 1)
           ->whereIn('b.estados_bines_id',[1,2,3,4])
            ->selectRaw('
                COUNT(a.id) as total_bines,
                COALESCE(SUM(CASE WHEN b.estados_bines_id = 4 THEN 1 ELSE 0 END), 0) as procesados,
                COALESCE(SUM(CASE WHEN b.estados_bines_id <> 4 THEN 1 ELSE 0 END), 0) as restantes,
                COALESCE(SUM(a.kilos_netos), 0) as kilos_totales
            ')
            ->first();

        return response()->json($resumen, 200);
    }


//************************************************************************************************************************************************************************************* */  
//************MOSTRAR TRANSPORTE PROCESO PALLET ******************************************************************************************************************************* */
/****************PALETIZADO******************************************************************************************************************************************************* */
/*********************************************************************************************************************************************************************************
 * **********************************************************************************************************************************************************************************
 * *********************************************************************************************************************************************************************************
 */

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
            ->join('tipos_contenedores as g','a.tipos_contenedores_id', '=', 'g.id')
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
                ->join('pallets as b','c.pallet_id', '=','b.id')
                ->join('despachos_pallets as dp', 'b.id', '=', 'dp.pallets_id')
                ->join('despachos as de', 'dp.despachos_id', '=', 'de.id')
                ->join('tipos_ubicaciones as t', 'c.pallet_id','=', 't.id' )
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