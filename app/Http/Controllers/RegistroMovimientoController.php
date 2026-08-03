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
    // Validamos que por lo menos venga un interviniente
    $request->validate([
        'roles' => 'required|array',
        'cedulas' => 'required|array',
        'nombres' => 'required|array',
        'cod_lib' => 'required',
        'num_rep' => 'required',
        'num_ins' => 'required',
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
            'movicodact' => $request->cod_acto,
            'moviobserv' => $request->observacion,
            'movicodcan' => $request->cod_can,
            'movicodjon' => $request->cod_jon,
            'movicodusu' => $codigoUsuario,
            
            
        ]);
        // 2. Vincular con la Ficha (sctndreff)
        DB::table('sctndreff')->insert([
            'refftipfic' => $request->tip_fic,
            'reffnumfic' => $request->fichnumfic, // Ajustado a "fichnumfic" como está en tu <input>
            'reffcodlib' => $request->cod_lib,
            'reffnumrep' => $request->num_rep,
            'refffecins' => $fechaInscripcion,
            'reffnumins' => $request->num_ins,
        ]);
        // 3. Sincronizar y Guardar Intervinientes (SCTNMCLIE y SCTNDCLMV)
        $roles = $request->input('roles');
        $cedulas = $request->input('cedulas');
        $nombres = $request->input('nombres');
        foreach ($cedulas as $index => $cedula) {
            $cedula = trim($cedula);
            $tipoCli = trim($roles[$index]);
            $nombre = trim($nombres[$index] ?? 'CLIENTE NUEVO');
            $secuencial = 1; // Por defecto asignamos el secuencial 1

            // A. Asegurar existencia en el catálogo maestro (sctnmclie)
            $clienteExiste = DB::table('sctnmclie')
                ->where('clietipcli', $tipoCli)
                ->where('cliecedruc', $cedula)
                ->where('clieseccli', $secuencial)
                ->exists();

            if (!$clienteExiste) {
                DB::table('sctnmclie')->insert([
                    'clietipcli' => 'S',
                    'cliecedruc' => $cedula,
                    'clieseccli' => $secuencial,
                    'clienombre' => mb_strtoupper($nombre),
                ]);
            }

            // B. Crear la relación en el detalle (sctndclmv)
            DB::table('sctndclmv')->insert([
                'clmvcodlib' => $request->cod_lib,
                'clmvnumrep' => $request->num_rep,
                'clmvfecins' => $fechaInscripcion,
                'clmvnumins' => $request->num_ins,
                'clmvtipcli' => 'S',
                'clmvcedruc' => $cedula,
                'clmvseccli' => $secuencial,
                'clmvcodtip' => $tipoCli,
            ]);
        }
    });
    return redirect()->back()->with('success', 'Movimiento e intervinientes registrados correctamente');
}
public function preview(Request $request)
{
    // 1. Validamos los campos antes de procesar
    $request->validate([
        'clmvcodlib' => 'required',
        'clmvnumrep' => 'required',
        'intervinientes' => 'required|array',
    ]);

    // 2. Mapeo de estados civiles para la vista previa
    $estadosCiviles = [
        'S' => 'SOLTERO/A',
        'C' => 'CASADO/A',
        'CA' => 'CASADO/A', // Mapeamos por si envían 'CA'
        'D' => 'DIVORCIADO/A',
        'V' => 'VIUDO/A',
        'U' => 'UNIÓN DE HECHO',
    ];

    // 3. Simulamos la estructura que generaría PostgreSQL
    $filasHtml = '';
    
    foreach ($request->intervinientes as $item) {
        // Consultamos solo los nombres para la previsualización
        $papel = DB::table('sctnmpape')->where('papecodtip', $item['codtip'])->value('papenombre');
        $cliente = DB::table('sctnmclie')
            ->where('clietipcli', $item['tipcli'])
            ->where('cliecedruc', $item['cedruc'])
            ->first();

        $estCivilTexto = $estadosCiviles[$item['estciv']] ?? 'N/A';

        $filasHtml .= "<tr>
            <td class='col-papel'>" . trim($papel) . ":</td>
            <td class='col-nombre'>" . trim($cliente->clienombre ?? 'NO ENCONTRADO') . "</td>
            <td class='col-cedula'><b>C.I/RUC:</b> " . trim($item['cedruc']) . "</td>
            <td class='col-est-civil'><b>EST. CIVIL:</b> " . $estCivilTexto . "</td>
        </tr>";
    }

    $tablaHtml = "<table class='tabla-intervinientes'>{$filasHtml}</table>";

    // 4. Retornamos el HTML maquetado exactamente igual al reporte final
    return response()->json([
        'status' => 'success',
        'html_intervinientes' => $tablaHtml,
        'num_repertorio' => $request->clmvnumrep,
        'num_inscripcion' => $request->clmvnumins,
        'fecha_inscripcion' => $request->clmvfecins,
        'observacion' => $request->clmvobserv,
    ]);
}



}