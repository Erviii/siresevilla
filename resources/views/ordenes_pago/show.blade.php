@extends('layouts.base')

@section('title', 'Orden de Pago - ' . $orden->numero_orden)

@section('content')
<div class="container py-3">
    <div class="text-end mb-3 d-print-none">
        <button onclick="window.print()" class="btn btn-primary"><i class="bi bi-printer-fill me-1"></i> Imprimir Orden</button>
        <a href="{{ route('ordenes-pago.index') }}" class="btn btn-outline-secondary">Volver</a>
    </div>

    {{-- Hoja de Orden de Pago con formato exacto al documento físico --}}
    <div class="card p-4 border border-dark bg-white text-dark mx-auto" style="max-width: 850px; font-family: Arial, sans-serif;">
        
        {{-- Encabezado --}}
        <div class="border border-dark text-center p-2 mb-2">
            <h6 class="fw-bold mb-1 text-uppercase">{{ $config->nombre_registro ?? 'REGISTRO MUNICIPAL DE LA PROPIEDAD DEL CANTON SEVILLA DON BOSCO' }}</h6>
            <h6 class="fw-bold mb-1">Solicitud de Inscripción o servicio de Registro de la Propiedad (Orden de Pago)</h6>
            <small class="fst-italic d-block">Sr. Registrador de la Propiedad del cantón Sevilla Don Bosco por medio del presente, solicito la inscripción en el Registro a su cargo del siguiente acto y/o contrato:</small>
        </div>

        {{-- Solicitante y Escudo --}}
        <div class="border border-dark p-2 mb-2">
            <div class="row align-items-center">
                <div class="col-8 border-end border-dark text-center">
                    <div class="mb-2">______________________________________<br><small>Firma del solicitante</small></div>
                    <div class="fw-bold text-uppercase mb-1">{{ $orden->nombre_solicitante }}</div>
                    <div class="small">NOMBRE DEL SOLICITANTE</div>
                    <div class="fw-bold mb-1">{{ $orden->cedula_solicitante }}</div>
                    <div class="small">Cédula del solicitante</div>
                    <div class="fw-bold">{{ $orden->correo_notificacion ?? '-' }}</div>
                    <div class="small">CORREO ELECTRONICO PARA NOTIFICACION</div>
                </div>
                <div class="col-4 text-center">
                    {{-- Espacio para Escudo Institucional --}}
                    <img src="{{ asset('images/logosevilla.png') }}" alt="Logo" style="max-height: 80px;" class="mb-2">
                    <div class="border-top border-dark pt-2 fw-bold">
                        Orden Nro: <span class="text-danger">{{ $orden->numero_orden ?? '______' }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Detalle del Acto / Contrato --}}
        <div class="border border-dark mb-2">
            <div class="bg-light fw-bold text-center border-bottom border-dark py-1 text-uppercase small">ACTO Y/O CONTRATO</div>
            <table class="table table-sm table-bordered mb-0 border-dark small">
                <tr>
                    <td class="fw-bold bg-light" style="width: 25%;">Otorgado por:</td>
                    <td>{{ strtoupper($orden->otorgado_por) }}</td>
                </tr>
                <tr>
                    <td class="fw-bold bg-light">A favor de:</td>
                    <td>{{ strtoupper($orden->a_favor_de) }}</td>
                </tr>
                <tr>
                    <td class="fw-bold bg-light">Cuantía:</td>
                    <td>{{ strtoupper($orden->cuantia_texto) }}</td>
                </tr>
                <tr>
                    <td class="fw-bold bg-light">Fecha Ingreso:</td>
                    <td>{{ mb_strtoupper($orden->fecha_ingreso->locale('es')->isoFormat('dddd, D [DE] MMMM [DEL] YYYY')) }}</td>
                </tr>
            </table>
        </div>

        {{-- Tabla de Rubros / Valores --}}
        <table class="table table-sm table-bordered border-dark text-center mb-2 small">
            <thead>
                <tr class="bg-light">
                    <th style="width: 10%;">ORDEN</th>
                    <th>DESCRIPCION</th>
                    <th style="width: 20%;">VALOR</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orden->detalles as $detalle)
                    <tr>
                        <td>{{ $detalle->orden }}</td>
                        <td class="text-start px-2">{{ strtoupper($detalle->descripcion) }}</td>
                        <td class="text-end px-2">$ {{ number_format($detalle->valor, 2) }}</td>
                    </tr>
                @endforeach
                {{-- Filas vacías para mantener el formato físico --}}
                @for($i = count($orden->detalles); $i < 4; $i++)
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                    </tr>
                @endfor
                <tr class="fw-bold">
                    <td colspan="2" class="text-end pe-3">TOTAL</td>
                    <td class="text-end px-2">$ {{ number_format($orden->total, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="text-end small mb-2">
            Elaborado por: <strong>{{ strtoupper($orden->elaborado_por) }}</strong>
        </div>

        {{-- Pie Legal del Art. 50 --}}
        <div class="border border-dark p-2 mb-2 small" style="font-size: 10px;">
            <div class="fw-bold text-uppercase mb-1">DOCUMENTO PARA USO EXCLUSIVO DEL REGISTRO MUNICIPAL DE LA PROPIEDAD</div>
            <p class="mb-0">El presente documento no es un comprobante de venta que acredite el pago de un servicio registral, cuando realice un pago por este concepto, exija la respectiva factura.</p>
        </div>

        {{-- Sección de Observaciones y Devolución --}}
        <div class="border border-dark p-2 small">
            <div class="fw-bold text-uppercase">OBSERVACIONES</div>
            <p class="mb-2 text-muted" style="font-size: 11px;">
                {{ $orden->observaciones ?? 'Visto la documentación ingresada para la inscripción en este Registro de la Propiedad, se verifica que no cumple con los Requisitos respectivos de acuerdo al siguiente detalle, por lo que se procede a devolver al interesado para su respectiva corrección.' }}
            </p>

            <table class="table table-sm table-bordered border-dark mb-2" style="font-size: 11px;">
                <tr>
                    <td class="fw-bold bg-light" style="width: 15%;">FACTURA</td>
                    <td style="width: 35%;">{{ $orden->factura_nro ?? '' }}</td>
                    <td class="fw-bold bg-light" style="width: 15%;">CEDULA:</td>
                    <td>{{ $orden->cedula_solicitante }}</td>
                </tr>
            </table>

            <div class="fw-bold text-danger text-uppercase mb-2">1.- DEVUELTO CONFORME:</div>
            <div class="row text-center mt-3">
                <div class="col-6">
                    <div class="border-bottom border-dark mb-1"></div>
                    <small class="fw-bold d-block">Firma</small>
                    <div class="mt-2 border-bottom border-dark mb-1"></div>
                    <small class="fw-bold d-block">Nombre: {{ $orden->devuelto_nombre ?? '' }}</small>
                </div>
                <div class="col-6">
                    <div class="border-bottom border-dark mb-1"></div>
                    <small class="fw-bold d-block">Cédula: {{ $orden->devuelto_cedula ?? '' }}</small>
                    <div class="mt-2 border-bottom border-dark mb-1"></div>
                    <small class="fw-bold d-block">Fecha: {{ $orden->devuelto_fecha ? $orden->devuelto_fecha->format('d/m/Y') : '' }}</small>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
@media print {
    .d-print-none, sidebar, nav, footer { display: none !important; }
    body { background: white !important; }
    .card { border: none !important; box-shadow: none !important; }
}
</style>
@endsection