<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\HistorialRegistrador; // Importamos el modelo
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteRegistralController extends Controller
{
    /**
     * Consulta el movimiento registral integrando Registrador Actual y Configuración.
     */
    private function obtenerDatosMovimiento($numrep, $fecins, $numins)
    {
        // 1. Datos Principales del Movimiento + Tablas Maestras
        $movimiento = DB::table('sctncmovi as m')
            ->join('sctnmlibr as l', 'l.librcodlib', '=', 'm.movicodlib')
            ->join('sctnmacto as a', 'a.actocodact', '=', 'm.movicodact')
            ->leftJoin('sctnmcant as c', 'c.cantcodcan', '=', 'm.movicodcan')
            ->leftJoin('sctnmjuno as j', 'j.junocodjon', '=', 'm.movicodjon')
            ->leftJoin('sctnmusua as u', 'u.usuacodusu', '=', 'm.movicodusu')
            ->where('m.movinumrep', $numrep)
            ->whereDate('m.movifecins', $fecins)
            ->where('m.movinumins', $numins)
            ->select(
                'm.movicodlib',
                'm.movinumrep',
                'm.movifecins',
                'm.movinumins',
                'm.movinumtom',
                'm.moviobserv',
                'm.movicodusu',
                'm.tip_doc',
                'm.fec_doc',
                'l.librnombre', 
                'a.actonombre', 
                'c.cantnombre', 
                'j.junonombre', 
                'u.usuanombre as nom_usuario'
            )
            ->first();

        if (!$movimiento) {
            return null;
        }

        // 2. Registrador Actual y Configuración Institucional
       /*$registradorActual = HistorialRegistrador::where('es_actual', true)->first();*/

$fechaInscripcion = $movimiento->movifecins;

   $registradorActual = HistorialRegistrador::where('fecha_inicio', '<=', $fechaInscripcion)
    ->where(function ($query) use ($fechaInscripcion) {
        $query->where('fecha_fin', '>=', $fechaInscripcion)
              ->orWhereNull('fecha_fin');
    })
    ->latest('fecha_inicio') // Garantiza traer el más reciente si hay coincidencia
    ->first();

    // Si por algún motivo no hay un registrador mapeado en esa fecha histórica,
    // tomamos el registrador marcado como 'es_actual' como fallback
    /*if (!$registradorActual) {
        $registradorActual = HistorialRegistrador::where('es_actual', true)->first();
    }*/




        $configuracion     = DB::table('configuraciones')->first();

        // 3. Ficha Registral / Predio Vinculado
        $fichaRef = DB::table('sctndreff as r')
            ->leftJoin('sctnmfich as f', 'f.fichnumfic', '=', 'r.reffnumfic')
            ->where('r.reffcodlib', $movimiento->movicodlib)
            ->where('r.reffnumrep', $numrep)
            ->whereDate('r.refffecins', $fecins)
            ->where('r.reffnumins', $numins)
            ->select('r.reffnumfic', 'f.*')
            ->first();

        // 4. Intervinientes del Movimiento
        $intervinientes = DB::table('sctndclmv as d')
            ->join('sctnmclie as c', function ($join) {
                $join->on('c.cliecedruc', '=', 'd.clmvcedruc')
                     ->on('c.clietipcli', '=', 'd.clmvtipcli')
                     ->on('c.clieseccli', '=', 'd.clmvseccli');
            })
            ->leftJoin('sctnmpape as p', 'p.papecodtip', '=', 'd.clmvcodtip')
            ->where('d.clmvcodlib', $movimiento->movicodlib)
            ->where('d.clmvnumrep', $numrep)
            ->whereDate('d.clmvfecins', $fecins)
            ->where('d.clmvnumins', $numins)
            ->select('c.cliecedruc', 'c.clienombre', 'p.papenombre')
            ->get();

        // 5. Generación del Código QR con datos configurados
        $numFichaTxt = $fichaRef->reffnumfic ?? 'S/N';
        $fecInscripcionTxt = date('d/m/Y H:i', strtotime($movimiento->movifecins));
        $institucionTxt = $configuracion->nombre_institucion ?? 'Reg. Propiedad Sevilla Don Bosco';

        $textoQr = "REGISTRO DE LA PROPIEDAD Y MERCANTIL\n"
                 . "SISTEMA DE INFORMACIÓN REGISTRAL (SIRE)\n"
                 . "Nro. Repertorio: " . $movimiento->movinumrep . "\n"
                 . "Nro. Inscripción: " . $movimiento->movinumins . "\n"
                 . "Nro. Ficha: SDB-" . $numFichaTxt . "\n"
                 . "Acto: " . $movimiento->actonombre . "\n"
                 . "Libro: " . $movimiento->librnombre . "\n"
                 . "Emisor: " . $institucionTxt . "\n"
                 . "Fecha Inscripción: " . $fecInscripcionTxt;

        $urlQr = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($textoQr);
        
        $qrBase64 = '';
        try {
            $qrImageContent = @file_get_contents($urlQr);
            if ($qrImageContent !== false) {
                $qrBase64 = base64_encode($qrImageContent);
            }
        } catch (\Exception $e) {
            $qrBase64 = '';
        }

        // Retornamos todas las variables requeridas para la vista
        return [
            'movimiento'         => $movimiento,
            'fichaRef'           => $fichaRef,
            'intervinientes'     => $intervinientes,
            'registradorActual'  => $registradorActual,
            'configuracion'      => $configuracion,
            'qrBase64'           => $qrBase64,
        ];
    }

    public function razonInscripcionPdf($numrep, $fecins, $numins)
    {
        $datos = $this->obtenerDatosMovimiento($numrep, $fecins, $numins);

        if (!$datos) {
            return redirect()->back()->with('error', 'El movimiento especificado no fue encontrado.');
        }

        $pdf = Pdf::loadView('sire.pdf.razon_inscripcion', $datos)->setPaper('a4', 'portrait')->setOption(['isPhpEnabled' => true]);
        return $pdf->stream("Razon_Inscripcion_{$numins}.pdf");
    }

    public function actaInscripcionPdf($numrep, $fecins, $numins)
    {
        $datos = $this->obtenerDatosMovimiento($numrep, $fecins, $numins);

        if (!$datos) {
            return redirect()->back()->with('error', 'El movimiento especificado no fue encontrado.');
        }

        $pdf = Pdf::loadView('sire.pdf.acta_inscripcion', $datos)->setPaper('a4', 'portrait')->setOption(['isPhpEnabled' => true]);
        return $pdf->stream("Acta_Inscripcion_{$numins}.pdf");
    }
}