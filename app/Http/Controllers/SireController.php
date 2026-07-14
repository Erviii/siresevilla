<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon; 

class SireController extends Controller
{
public function indexDashboard()
    {
        // 1. Fichas Totales
        $cantFichas = DB::table('sctnmfich')->count();

        // 2. Movimientos Hoy
        $hoy = Carbon::now()->format('Y-m-d');
        $movimientosHoy = DB::table('sctncmovi')
            ->whereDate('movifecins', $hoy)
            ->count();

        // 3. Clientes Únicos
        $cantClientes = DB::table('sctnmusua')->count(); 

        // 4. Libros Activos
        $cantLibros = DB::table('sctnmlibr')->count();

        // 5. Últimos Movimientos
        $ultimosMovimientos = DB::table('sctncmovi as mov')
            ->leftJoin('sctnmacto as act', 'mov.movicodact', '=', 'act.actocodact')
            ->leftJoin('sctnmlibr as lib', 'mov.movicodlib', '=', 'lib.librcodlib')
            ->select(
                'mov.movinumrep as movinumrep', 
                'mov.movinumins as movinumins', 
                'mov.movifecins as movifecins',
                'lib.librnombre as librnombre',
                'act.actonombre as actonombre'
            )
            ->orderBy('mov.movifecins', 'desc')
            ->limit(5)
            ->get();

        return view('sire.dashboard', compact(
            'cantFichas', 
            'movimientosHoy', 
            'cantClientes', 
            'cantLibros', 
            'ultimosMovimientos'
        ));
    }




// Consulta base con todos los JOINs para reutilizar en ambos métodos
    private function getBaseQuery()
{
    return "
        SELECT 
            cli.CLIECEDRUC AS cedula_ruc,
            cli.CLIENOMBRE AS nombre_cliente,
            ref.REFFNUMFIC AS numero_ficha,
            mov.MOVINUMREP AS num_repertorio,
            mov.MOVINUMINS AS num_inscripcion,
            mov.MOVIFECINS AS fecha_inscripcion,
            act.ACTONOMBRE AS tipo_acto,
            lib.LIBRNOMBRE AS libro,
            can.CANTNOMBRE AS canton,
            juz.JUNONOMBRE AS juzgado,
            usu.USUANOMBRE AS usuario,
            mov.MOVIOBSERV AS observacion  -- <--- AGREGA ESTA LÍNEA (VERIFICA EL NOMBRE)
        FROM SCTNCMOVI mov
        INNER JOIN SCTNDCLMV det ON mov.MOVICODLIB = det.CLMVCODLIB 
                                  AND mov.MOVINUMREP = det.CLMVNUMREP 
                                  AND mov.MOVIFECINS = det.CLMVFECINS 
                                  AND mov.MOVINUMINS = det.CLMVNUMINS
        INNER JOIN SCTNMCLIE cli ON det.CLMVTIPCLI = cli.CLIETIPCLI 
                                  AND det.CLMVCEDRUC = cli.CLIECEDRUC 
                                  AND det.CLMVSECCLI = cli.CLIESECCLI
        LEFT JOIN SCTNDREFF ref ON mov.MOVICODLIB = ref.REFFCODLIB 
                                AND mov.MOVINUMREP = ref.REFFNUMREP 
                                AND mov.MOVIFECINS = ref.REFFFECINS 
                                AND mov.MOVINUMINS = ref.REFFNUMINS
        LEFT JOIN SCTNMACTO act ON mov.MOVICODACT = act.ACTOCODACT
        LEFT JOIN SCTNMLIBR lib ON mov.MOVICODLIB = lib.LIBRCODLIB
        LEFT JOIN SCTNMCANT can ON mov.MOVICODCAN = can.CANTCODCAN
        LEFT JOIN SCTNMJUNO juz ON mov.MOVICODJON = juz.JUNOCODJON
        LEFT JOIN SCTNMUSUA usu ON mov.MOVICODUSU = usu.USUACODUSU
    ";
}


private function getBaseQueryReporte()
{
    // Usamos DISTINCT ON pero añadiendo el Código del Acto (MOVICODACT)
    // Así evitamos que actos distintos con el mismo número desaparezcan.
    return "
        SELECT DISTINCT ON (mov.MOVINUMREP, mov.MOVINUMINS, mov.MOVICODACT)
            cli_agrupados.nombre_cliente,
            ref.REFFNUMFIC AS numero_ficha,
            mov.MOVINUMREP AS num_repertorio,
            mov.MOVINUMINS AS num_inscripcion,
            mov.MOVIFECINS AS fecha_inscripcion,
            act.ACTONOMBRE AS tipo_acto,
            lib.LIBRNOMBRE AS libro,
            can.CANTNOMBRE AS canton,
            juz.JUNONOMBRE AS juzgado,
            usu.USUANOMBRE AS usuario,
            mov.MOVIOBSERV AS observacion,
            fich.FICHLINREG AS linderos
        FROM SCTNCMOVI mov
        LEFT JOIN (
            SELECT 
                det.CLMVCODLIB, det.CLMVNUMREP, det.CLMVFECINS, det.CLMVNUMINS,
                STRING_AGG(DISTINCT TRIM(cli.CLIENOMBRE::text) || ' (CI/RUC: ' || TRIM(cli.CLIECEDRUC::text) || ')', '<br>') AS nombre_cliente
            FROM SCTNDCLMV det
            INNER JOIN SCTNMCLIE cli ON det.CLMVTIPCLI = cli.CLIETIPCLI 
                                    AND det.CLMVCEDRUC = cli.CLIECEDRUC 
                                    AND det.CLMVSECCLI = cli.CLIESECCLI
            GROUP BY det.CLMVCODLIB, det.CLMVNUMREP, det.CLMVFECINS, det.CLMVNUMINS
        ) AS cli_agrupados ON mov.MOVICODLIB = cli_agrupados.CLMVCODLIB 
                           AND mov.MOVINUMREP = cli_agrupados.CLMVNUMREP
                           AND mov.MOVIFECINS = cli_agrupados.CLMVFECINS
                           AND mov.MOVINUMINS = cli_agrupados.CLMVNUMINS
        LEFT JOIN SCTNDREFF ref ON mov.MOVICODLIB = ref.REFFCODLIB 
                                AND mov.MOVINUMREP = ref.REFFNUMREP 
                                AND mov.MOVIFECINS = ref.REFFFECINS 
                                AND mov.MOVINUMINS = ref.REFFNUMINS
        LEFT JOIN SCTNMACTO act ON mov.MOVICODACT = act.ACTOCODACT
        LEFT JOIN SCTNMLIBR lib ON mov.MOVICODLIB = lib.LIBRCODLIB
        LEFT JOIN SCTNMCANT can ON mov.MOVICODCAN = can.CANTCODCAN
        LEFT JOIN SCTNMJUNO juz ON mov.MOVICODJON = juz.JUNOCODJON
        LEFT JOIN SCTNMUSUA usu ON mov.MOVICODUSU = usu.USUACODUSU
        LEFT JOIN SCTNMFICH fich ON ref.REFFNUMFIC = fich.FICHNUMFIC
    ";
}

    // 1. Pantalla principal del buscador
    public function index()
    {
        return view('sire.index');
    }

    // 2. Método exclusivo para buscar por Cédula / RUC
    public function buscarPorCedula(Request $request)
    {
       /* $request->validate(['cedula' => 'required|numeric']);
        $cedula = $request->input('cedula');*/
        $request->validate(['valor_busqueda' => 'required']);
    $cedula = $request->input('valor_busqueda'); // Usamos 'valor_busqueda'

        $sql = $this->getBaseQuery() . " WHERE cli.CLIECEDRUC = ? ORDER BY mov.MOVIFECINS DESC";
       /* $resultados = DB::select($sql, [$cedula]);

        return view('sire.index', [
            'resultados' => $resultados,
            'valor' => $cedula,
            'tipo' => 'cedula'
        ]);*/
        // ... tu código sql ...
    $resultadosRaw = DB::select($sql, [$cedula]); // O como se llame tu variable

    // NUEVO: Agrupamos usando la función privada
    $resultadosAgrupados = $this->agruparResultadosRegistrales($resultadosRaw);

    return view('sire.index', [
        'resultados' => $resultadosAgrupados,
        'valor' => $cedula,
        'tipo' => 'cedula'
    ]);



        
    }

    // 3. Método exclusivo para buscar por Número de Ficha
    public function buscarPorFicha(Request $request)
    {
        /*$request->validate(['ficha' => 'required|numeric']);
    $ficha = $request->input('ficha');*/
        $request->validate(['valor_busqueda' => 'required']);
    $ficha = $request->input('valor_busqueda'); // Usamos 'valor_busqueda'

    // 1. Obtenemos la consulta base
    $sql = $this->getBaseQuery();

    ////nuevo codigo

    // IMPORTANTE: Cambiamos el ORDER BY final a ASC para ir de la fecha más antigua a la más reciente
    $sql .= " WHERE ref.REFFNUMFIC = ? 
              ORDER BY mov.MOVIFECINS ASC, mov.MOVINUMREP, mov.MOVINUMINS";

    $resultadosRaw = DB::select($sql, [$ficha]);

    // PROCESAMIENTO CON LARAVEL:
    // 1. Agrupamos primero por Ficha (numero_ficha)
    // 2. Dentro de cada ficha, agrupamos por una clave única compuesta por el Repertorio e Inscripción
    $resultadosAgrupados = collect($resultadosRaw)
        ->groupBy('numero_ficha')
        ->map(function ($fichas) {
            return $fichas->groupBy(function ($item) {
                // Creamos una clave única para identificar el movimiento exacto
                return $item->num_repertorio . '-' . $item->num_inscripcion;
            });
        });

    return view('sire.index', [
        'resultados' => $resultadosAgrupados, // Enviamos la colección con doble agrupación
        'valor' => $ficha,
        'tipo' => 'ficha'
    ]);   

    }


public function imprimirReporteFicha($ficha)
{
    $datosFicha = DB::table('sctnmfich')->where('fichnumfic', $ficha)->first();
    
    // ATENCIÓN AQUÍ: 
    // 1. La subconsulta interna limpia los duplicados agrupando por Rep, Ins y Acto.
    // 2. La consulta externa (resultado_limpio) ordena todo por fecha del más antiguo al más nuevo.
    $sql = "SELECT * FROM (" . $this->getBaseQueryReporte() . " 
                WHERE ref.REFFNUMFIC = ? 
                ORDER BY mov.MOVINUMREP, mov.MOVINUMINS, mov.MOVICODACT, mov.MOVIFECINS DESC
            ) AS resultado_limpio
            ORDER BY resultado_limpio.fecha_inscripcion ASC"; 
           
    $resultados = DB::select($sql, [$ficha]);

    // Resumen de actos
    $resumen = collect($resultados)->groupBy('tipo_acto')->map(function ($items) {
        return $items->count();
    });

    // Código de barras
    $url_imagen = "https://barcode.tec-it.com/barcode.ashx?data=" . $ficha . "&code=Code128&dpi=96";
    $imagenCodigoBarras = base64_encode(file_get_contents($url_imagen));

    $pdf = Pdf::loadView('sire.reporte-ficha', compact('resultados', 'datosFicha', 'ficha', 'resumen', 'imagenCodigoBarras'));
    return $pdf->stream("Reporte_Ficha_{$ficha}.pdf");
}


public function buscarNombre(Request $request)
{
    /*$request->validate(['nombre' => 'required|string|min:3']);
    $nombre = '%' . $request->input('nombre') . '%';*/

        $request->validate(['valor_busqueda' => 'required']);
    $nombre = '%' . $request->input('valor_busqueda') . '%';// Usamos 'valor_busqueda'

    // Obtenemos la consulta base y aplicamos DISTINCT ON para eliminar duplicados
    $sql = $this->getBaseQuery();
    $sql = str_replace('SELECT', 'SELECT DISTINCT ON (mov.MOVINUMREP, mov.MOVINUMINS)', $sql);
    
    // Filtro por nombre
    $sql .= " WHERE cli.CLIENOMBRE ILIKE ? ORDER BY mov.MOVINUMREP, mov.MOVINUMINS, mov.MOVIFECINS DESC";

   /* $resultados = DB::select($sql, [$nombre]);

    return view('sire.index', [
        'resultados' => $resultados,
        'valor' => $request->input('nombre'),
        'tipo' => 'nombre'
    ]);*/
      // ... tu código sql ...
    $resultadosRaw = DB::select($sql, [$nombre]); // O como se llame tu variable

    // NUEVO: Agrupamos usando la función privada
    $resultadosAgrupados = $this->agruparResultadosRegistrales($resultadosRaw);

    return view('sire.index', [
        'resultados' => $resultadosAgrupados,
        'valor' => $nombre,
        'tipo' => 'nombre'
    ]);
}


/**
     * Función auxiliar para agrupar los resultados en Fichas -> Movimientos
     */
    private function agruparResultadosRegistrales($resultadosRaw)
    {
        return collect($resultadosRaw)
            ->groupBy('numero_ficha')
            ->map(function ($fichas) {
                return $fichas->groupBy(function ($item) {
                    return $item->num_repertorio . '-' . $item->num_inscripcion;
                });
            });
    }



    
    
}
