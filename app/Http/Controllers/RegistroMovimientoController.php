<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RegistroMovimientoController extends Controller
{
    /**
     * Muestra el formulario de registro.
     */
   

public function indexregistrar($fichaNum)
    {
      
            // Buscar los datos de la Ficha actual
            $ficha = DB::table('sctnmfich')
                ->where('fichnumfic', $fichaNum)
                ->first();

            if (!$ficha) {
                return redirect()->back()->with('error', 'La Ficha especificada no existe.');
            }

            // Consultas a tablas maestras
            $libros = DB::table('sctnmlibr')->orderBy('librnombre', 'asc')->get();
            $actos = DB::table('sctnmacto')->orderBy('actonombre', 'asc')->get();
            $cantones = DB::table('sctnmcant')->orderBy('cantnombre', 'asc')->get();
            $juzgados = DB::table('sctnmjuno')->orderBy('junonombre', 'asc')->get();
            //SCTNMPAPE
            $rolcliente = DB::table('sctnmpape')->orderBy('papenombre', 'asc')->get();



            return view('sire.registrar_mov_ficha', compact('ficha', 'libros', 'actos', 'cantones', 'juzgados','rolcliente'));


    }
    /**
     * Procesa la inserción del movimiento y los intervinientes.
     */

public function storeMovimiento(Request $request)
{
    // Validamos que vengan los datos obligatorios
    $request->validate([
        'roles'   => 'required|array',
        'cedulas' => 'required|array',
        'nombres' => 'required|array',
        'cod_lib' => 'required',
        'num_rep' => 'required',
        'num_ins' => 'required',
        'num_tom' => 'required', // <-- Agregamos el tomo a la validación
    ]);

    DB::transaction(function () use ($request) {

        // Accedes directamente a la columna de la base de datos desde el usuario autenticado
        $codigoUsuario = Auth::user()->usuacodusu;
        
        // Usamos la fecha de inscripción que viene del formulario, o la actual si falla
        $fechaInscripcion = $request->input('fec_ins') ?? date('Y-m-d');
        
        // 1. Insertar Movimiento Principal (sctncmovi)
        DB::table('sctncmovi')->insert([
            'movicodlib' => $request->cod_lib,
            'movinumrep' => $request->num_rep,
            'movifecins' => $fechaInscripcion, 
            'movinumins' => $request->num_ins,
            'movinumtom' => $request->num_tom,  
            'movicodact' => $request->cod_acto,
            'moviobserv' => $request->observacion,
            'movicodcan' => $request->cod_can,
            'movicodjon' => $request->cod_jon,
            'movicodusu' => $codigoUsuario,
        ]);
        
        // 2. Vincular con la Ficha (sctndreff)
        DB::table('sctndreff')->insert([
            'refftipfic' => $request->tip_fic,
            'reffnumfic' => $request->fichnumfic,
            'reffcodlib' => $request->cod_lib,
            'reffnumrep' => $request->num_rep,
            'refffecins' => $fechaInscripcion,
            'reffnumins' => $request->num_ins,
            'reffnumtom' => $request->num_tom,  
        ]);
        
        // 3. Sincronizar y Guardar Intervinientes (SCTNMCLIE y SCTNDCLMV)
        $roles   = $request->input('roles');
        $cedulas = $request->input('cedulas');
        $nombres = $request->input('nombres');
        
        // Arreglo para llevar el control de clientes procesados en este request y evitar duplicados
        $clientesProcesados = []; 

        foreach ($cedulas as $index => $cedula) {
            $cedula     = trim($cedula);
            $tipoCli    = trim($roles[$index]);
            $nombre     = trim($nombres[$index] ?? 'CLIENTE NUEVO');
            $secuencial = 1; // Por defecto asignamos el secuencial 1

            // Solo verificamos e insertamos en la maestra si no lo hemos procesado en este ciclo
            if (!in_array($cedula, $clientesProcesados)) {
                
                // A. Asegurar existencia en el catálogo maestro (sctnmclie) BUSCANDO SOLO POR CÉDULA
                $clienteExiste = DB::table('sctnmclie')
                    ->where('cliecedruc', $cedula)
                    ->where('clietipcli', 'S') //IDENTIFICA QUE YA HALLA SIDO AGREGADO POR NUESTRA BASE DE DATOS.PARA LOS REPORTES.
                    ->exists();

                if (!$clienteExiste) {
                    DB::table('sctnmclie')->insert([
                        'clietipcli' => 'S', // Valor estándar para la tabla maestra
                        'cliecedruc' => $cedula,
                        'clieseccli' => $secuencial,
                        'clienombre' => mb_strtoupper($nombre),
                    ]);
                }
                
                // Lo marcamos como procesado para que, si la misma persona tiene otro rol abajo, no intente volver a crearla
                $clientesProcesados[] = $cedula;
            }

            // B. Crear la relación en el detalle (sctndclmv) - ESTO SÍ SE EJECUTA SIEMPRE
            DB::table('sctndclmv')->insert([
                'clmvcodlib' => $request->cod_lib,
                'clmvnumrep' => $request->num_rep,
                'clmvfecins' => $fechaInscripcion,
                'clmvnumins' => $request->num_ins,
                'clmvtipcli' => 'S', 
                'clmvcedruc' => $cedula,
                'clmvseccli' => $secuencial,
                'clmvcodtip' => $tipoCli, // Aquí guardamos el rol (COMPRADOR, VENDEDOR, etc.)
            ]);
        }
    });
    
    return redirect()->back()->with('success', 'Movimiento e intervinientes registrados correctamente');
}

public function preview(Request $request)
{
    // 1. Validación de campos
    $request->validate([
        'cod_lib'      => 'required',
        'num_rep'      => 'required',
        'num_ins'      => 'required',
        'fec_ins'      => 'required|date',
        'cod_acto'     => 'required',
        'roles'        => 'required|array',
        'cedulas'      => 'required|array',
        'nombres'      => 'required|array',
    ]);

    // 2. Consultar el nombre del Tipo de Acto
    $nombreActo = DB::table('sctnmacto') // Ajusta el nombre de la tabla si difiere
        ->where('actocodact', $request->input('cod_acto'))
        ->value('actonombre');

    // 3. Mapeo de intervinientes
    $roles = $request->input('roles', []);
    $cedulas = $request->input('cedulas', []);
    $nombres = $request->input('nombres', []);

    $filasHtml = '';
    foreach ($roles as $index => $codTip) {
        $cedula = $cedulas[$index] ?? '';
        $nombre = $nombres[$index] ?? '';

        $papelNombre = DB::table('sctnmpape')
            ->where('papecodtip', $codTip)
            ->value('papenombre');

        $papelTexto = $papelNombre ? trim($papelNombre) : 'INTERVINIENTE';

        $filasHtml .= "<tr>
            <td class='col-papel'><b>" . e($papelTexto) . ":</b></td>
            <td class='col-nombre'>" . e(trim($nombre)) . "</td>
            <td class='col-cedula'><b>C.I/RUC:</b> " . e(trim($cedula)) . "</td>
        </tr>";
    }

    $tablaHtml = "<table class='table table-sm table-striped align-middle mb-0'>{$filasHtml}</table>";

    // 4. Retorno JSON con Tipo de Acto y Observaciones
    return response()->json([
        'status'             => 'success',
        'html_intervinientes'=> $tablaHtml,
        'num_repertorio'     => $request->input('num_rep'),
        'num_inscripcion'    => $request->input('num_ins'),
        'fecha_inscripcion'  => $request->input('fec_ins'),
        'tipo_acto'          => $nombreActo ?? 'NO ESPECIFICADO',
        'observacion'        => $request->input('observacion') ?? 'Sin observaciones.',
    ]);
}



}