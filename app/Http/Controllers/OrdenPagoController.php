<?php

namespace App\Http\Controllers;

use App\Models\OrdenPago;
use App\Models\OrdenPagoDetalle;
use App\Models\Configuracion;
use App\Models\HistorialRegistrador;
use App\Services\ArancelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrdenPagoController extends Controller
{
    public function index(Request $request)
    {
       /* $ordenes = OrdenPago::orderBy('created_at', 'desc')->paginate(15);
        return view('ordenes_pago.index', compact('ordenes'));*/

        $query = OrdenPago::query();

    // Filtros de búsqueda
    if ($request->filled('buscar')) {
        $buscar = $request->buscar;
        $query->where(function($q) use ($buscar) {
            $q->where('numero_orden', 'like', "%{$buscar}%")
              ->orWhere('cedula_solicitante', 'like', "%{$buscar}%")
              ->orWhere('nombre_solicitante', 'like', "%{$buscar}%");
        });
    }

    if ($request->filled('estado')) {
        $query->where('estado', $request->estado);
    }

    // 1. Cálculo de Indicadores (KPIs)
    $totalesKPI = [
        'total_generadas' => OrdenPago::count(),
        'por_recaudar'    => OrdenPago::where('estado', 'PENDIENTE')->sum('total'),
        'total_pagado'     => OrdenPago::where('estado', 'PAGADO')->sum('total'),
        'cant_pendientes'  => OrdenPago::where('estado', 'PENDIENTE')->count(),
        'cant_pagadas'     => OrdenPago::where('estado', 'PAGADO')->count(),
    ];

    // 2. Obtener listado paginado
    $ordenes = $query->orderBy('created_at', 'desc')->paginate(15);

    return view('ordenes_pago.index', compact('ordenes', 'totalesKPI'));
        
    }

    public function create()
    {
        $servicios = DB::table('aranceles_servicios')->where('activo', true)->get();
        $nuevoNumero = OrdenPago::generarNumeroOrden();

        return view('ordenes_pago.create', compact('servicios', 'nuevoNumero'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_solicitante' => 'required|string|max:255',
            'cedula_solicitante' => 'required|string|max:13',
            'otorgado_por' => 'required|string|max:255',
            'a_favor_de' => 'required|string|max:255',
            'tipo_acto' => 'required|exists:aranceles_servicios,codigo',
            'fecha_ingreso' => 'required|date',
        ]);

        $cuantiaMonto = $request->cuantia_monto ?? 0;

        // Calcular aranceles con el servicio parametrizado
        $calculo = ArancelService::calcular(
            $request->tipo_acto,
            $cuantiaMonto,
            $request->cantidad_predios ?? 1,
            $request->tipo_exoneracion ?? 'NINGUNA'
        );

        DB::transaction(function () use ($request, $calculo, &$orden) {
            $orden = OrdenPago::create([
                'numero_orden' => OrdenPago::generarNumeroOrden(),
                'factura_nro' => $request->factura_nro,
                'nombre_solicitante' => $request->nombre_solicitante,
                'cedula_solicitante' => $request->cedula_solicitante,
                'correo_notificacion' => $request->correo_notificacion,
                'otorgado_por' => $request->otorgado_por,
                'a_favor_de' => $request->a_favor_de,
                'cuantia_texto' => $request->cuantia_texto ?? 'INDETERMINADA',
                'cuantia_monto' => $calculo['subtotal'],
                'fecha_ingreso' => $request->fecha_ingreso,
                'subtotal' => $calculo['subtotal'],
                'descuento' => $calculo['descuento'],
                'total' => $calculo['total'],
                'estado' => 'PENDIENTE',
                'elaborado_por' => Auth::user()->name ?? 'sistemas',
            ]);

            // Obtener nombre del servicio/acto
            $servicio = DB::table('aranceles_servicios')->where('codigo', $request->tipo_acto)->first();

            // Insertar detalle del acto en la tabla secundaria
            OrdenPagoDetalle::create([
                'orden_pago_id' => $orden->id,
                'orden' => 1,
                'codigo_servicio' => $request->tipo_acto,
                'descripcion' => $servicio->nombre ?? 'ACTO REGISTRAL',
                'valor' => $calculo['total'],
            ]);
        });

        return redirect()->route('ordenes-pago.show', $orden->id)
                         ->with('success', 'Orden de Pago generada correctamente.');
    }

    public function show($id)
    {
        $orden = OrdenPago::with('detalles')->findOrFail($id);
        $config = Configuracion::first();
        $registrador = HistorialRegistrador::actual()->first();

        return view('ordenes_pago.show', compact('orden', 'config', 'registrador'));
    }

    public function calcularAjax(Request $request)
    {
        $calculo = ArancelService::calcular(
            $request->tipo_acto,
            $request->cuantia ?? 0,
            $request->cantidad_predios ?? 1,
            $request->tipo_exoneracion ?? 'NINGUNA'
        );

        return response()->json($calculo);
    }

/**
     * Registrar el número de factura de Tesorería y cambiar estado a PAGADO
     */
    public function registrarFactura(Request $request, $id)
    {
        $request->validate([
            'factura_nro' => 'required|string|max:50',
        ]);

        $orden = OrdenPago::findOrFail($id);
        
        $orden->update([
            'factura_nro' => $request->factura_nro,
            'estado' => 'PAGADO',
        ]);

        return redirect()->back()->with('success', 'Factura N° ' . $request->factura_nro . ' registrada. La orden de pago cambió a estado PAGADO.');
    }


    // Método para registrar la devolución de una orden pagada
public function devolverTramite(Request $request, $id)
{
    $request->validate([
        'observaciones' => 'required|string',
        'devuelto_nombre' => 'required|string|max:255',
        'devuelto_cedula' => 'required|string|max:13',
        'devuelto_fecha' => 'required|date',
    ]);

    $orden = OrdenPago::findOrFail($id);

    $orden->update([
        'estado' => 'DEVUELTO',
        'observaciones' => $request->observaciones,
        'devuelto_nombre' => $request->devuelto_nombre,
        'devuelto_cedula' => $request->devuelto_cedula,
        'devuelto_fecha' => $request->devuelto_fecha,
    ]);

    return redirect()->back()->with('success', 'Trámite marcado como DEVUELTO con sus respectivas observaciones.');
}

/**
     * Cambiar el estado de la orden (Interno)
     */
    public function cambiarEstado(Request $request, $id)
    {
        $request->validate([
            'nuevo_estado' => 'required|string',
        ]);

        $orden = OrdenPago::findOrFail($id);
        $orden->update(['estado' => $request->nuevo_estado]);

        return redirect()->back()->with('success', 'El estado del trámite cambió a: ' . $request->nuevo_estado);
    }

    /**
     * Consulta Pública para el ciudadano
     */
    public function consultarEstado(Request $request)
    {
        $orden = null;
        $buscado = false;

        if ($request->filled('criterio')) {
            $buscado = true;
            $criterio = trim($request->criterio);

            $orden = OrdenPago::with('detalles')
                ->where('numero_orden', 'ILIKE', "%{$criterio}%")
                ->orWhere('cedula_solicitante', $criterio)
                ->orderBy('created_at', 'desc')
                ->first();
        }

        return view('ordenes_pago.consulta_publica', compact('orden', 'buscado'));
    }

}