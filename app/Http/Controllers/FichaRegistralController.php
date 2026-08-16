<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FichaRegistralController extends Controller
{
    /**
     * Muestra el formulario para aperturar una nueva Ficha.
     */
    public function create()
    {
       // Obtenemos los cantones/parroquias de la tabla maestra sctnmcant
    $cantones = DB::table('sctnmcant')->orderBy('cantnombre', 'asc')->get();

    // Obtener el último número de ficha para sugerir el siguiente
    $ultimoNumFicha = DB::table('sctnmfich')->max('fichnumfic') ?? 0;
    $nuevoNumFicha = $ultimoNumFicha + 1;

    return view('sire.apertura_ficha', compact('nuevoNumFicha', 'cantones'));
    }

    /**
     * Guarda la nueva Ficha Registral con la estructura exacta de sctnmfich.
     */
   
public function store(Request $request)
{
    $request->validate([
        'fichtippre' => 'required|in:R,U',
        'fichcodigo' => 'required|string|max:100',
        'fichcodpar' => 'required',
        'norte'      => 'required|string',
        'sur'        => 'required|string',
        'este'       => 'required|string',
        'oeste'      => 'required|string',
        'area_total' => 'required|string',
    ]);

    // Ejecutamos la transacción y capturamos el valor retornado
    $numFichaGenerado = DB::transaction(function () use ($request) {
        
        // 1. Obtener el número secuencial más alto y sumar 1
        $numFicha = (DB::table('sctnmfich')->max('fichnumfic') ?? 0) + 1;

        // 2. Definir fecha
        $fechaActual = $request->fichfecape ?? date('Y-m-d H:i:s');

        // 3. Obtener nombre del cantón/parroquia
        $cantonObj = DB::table('sctnmcant')->where('cantcodcan', $request->fichcodpar)->first();
        $nombreCanton = $cantonObj->cantnombre ?? 'SEVILLA DON BOSCO';

        $sector = $request->input('sector', 'Sevilla Don Bosco');

        // 4. Construir linderos
        $linderosTexto = "Lote ubicado en el sector " . trim($sector) . ", parroquia " . trim($nombreCanton) . ".\n"
                       . "NORTE : " . trim($request->norte) . "\n"
                       . "SUR : " . trim($request->sur) . "\n"
                       . "ESTE : " . trim($request->este) . "\n"
                       . "OESTE : " . trim($request->oeste) . "\n"
                       . "Área total: " . trim($request->area_total);

        // 5. Inserción en sctnmfich
        DB::table('sctnmfich')->insert([
            'fichtipfic' => 0,
            'fichnumfic' => $numFicha,
            'fichcodigo' => trim($request->fichcodigo),
            'fichfecape' => $fechaActual,
            'fichcodpar' => $request->fichcodpar,
            'fichcodcdl' => 1,
            'fichlinreg' => $linderosTexto,
            'fichtippre' => $request->fichtippre,
            'fichtipdep' => $request->fichtipdep ?? 0,
            'fichstatus' => 'AC',
            'fichfecmov' => $fechaActual,
            'fichdetfun' => $request->fichdetfun ?? 'APERTURA DE NUEVA FICHA REGISTRAL',
            'fichinfmun' => $request->fichinfmun ?? null,
            'fichcodlib' => $request->fichcodlib ?? null,
            'fichnumins' => $request->fichnumins ?? null,
            'fichfecins' => $request->fichfecins ?? null,
            'fichnumrep' => $request->fichnumrep ?? null,
            'fichindice' => $numFicha,
        ]);

        // Retornamos el número de ficha generado desde la transacción
        return $numFicha;
    });

    // Redirección garantizada con la variable devuelta
    return redirect()->route('sire.registrar', ['fichaNum' => $numFichaGenerado])
        ->with('success', "Ficha Registral N° SDB-{$numFichaGenerado} aperturada correctamente.");
}
}