@extends('layouts.base')

@section('title', 'Órdenes de Pago')

@section('content')
<div class="container-fluid py-4">

    {{-- Alert Mensajes de Éxito --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

{{-- PANEL DE INDICADORES / KPIS --}}
<div class="row g-3 mb-4">
    {{-- Indicador 1: Órdenes Generadas --}}
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="card border-0 shadow-sm border-start border-4 border-primary h-100">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-semibold small text-uppercase d-block mb-1">Órdenes Generadas</span>
                    <h3 class="mb-0 fw-bold text-dark">{{ number_format($totalesKPI['total_generadas']) }}</h3>
                    <small class="text-muted fs-7">
                        <span class="text-success fw-bold">{{ $totalesKPI['cant_pagadas'] }} pagadas</span> | 
                        <span class="text-warning fw-bold">{{ $totalesKPI['cant_pendientes'] }} pendientes</span>
                    </small>
                </div>
                <div class="rounded-circle bg-primary bg-opacity-10 p-3 text-primary">
                    <i class="bi bi-file-earmark-text-fill fs-2"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Indicador 2: Valor Por Recaudar --}}
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="card border-0 shadow-sm border-start border-4 border-warning h-100">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-semibold small text-uppercase d-block mb-1">Por Recaudar (Pendiente)</span>
                    <h3 class="mb-0 fw-bold text-warning">$ {{ number_format($totalesKPI['por_recaudar'], 2) }}</h3>
                    <small class="text-muted fs-7">Órdenes a la espera de pago en Tesorería</small>
                </div>
                <div class="rounded-circle bg-warning bg-opacity-10 p-3 text-warning">
                    <i class="bi bi-hourglass-split fs-2"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Indicador 3: Valor Pagado / Recaudado --}}
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="card border-0 shadow-sm border-start border-4 border-success h-100">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-semibold small text-uppercase d-block mb-1">Total Recaudado (Pagado)</span>
                    <h3 class="mb-0 fw-bold text-success">$ {{ number_format($totalesKPI['total_pagado'], 2) }}</h3>
                    <small class="text-muted fs-7">Ingresos confirmados con factura</small>
                </div>
                <div class="rounded-circle bg-success bg-opacity-10 p-3 text-success">
                    <i class="bi bi-cash-stack fs-2"></i>
                </div>
            </div>
        </div>
    </div>
</div>

    {{-- Tarjeta Principal --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0 font-weight-bold">
                <i class="bi bi-receipt-cutoff me-2"></i> Gestión de Órdenes de Pago (Solicitudes)
            </h5>
            <a href="{{ route('ordenes-pago.create') }}" class="btn btn-light text-primary font-weight-bold shadow-sm">
                <i class="bi bi-plus-circle-fill me-1"></i> Nueva Orden de Pago
            </a>
        </div>

        <div class="card-body">

            {{-- Formulario de Búsqueda / Filtros --}}
            <form action="{{ route('ordenes-pago.index') }}" method="GET" class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" name="buscar" class="form-control" placeholder="Buscar por N° Orden, Cédula o Solicitante..." value="{{ request('buscar') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="estado" class="form-select">
                        <option value="">-- Todos los Estados --</option>
                        <option value="PENDIENTE" {{ request('estado') == 'PENDIENTE' ? 'selected' : '' }}>PENDIENTE</option>
                        <option value="PAGADO" {{ request('estado') == 'PAGADO' ? 'selected' : '' }}>PAGADO</option>
                        <option value="DEVUELTO" {{ request('estado') == 'DEVUELTO' ? 'selected' : '' }}>DEVUELTO</option>
                        <option value="ANULADO" {{ request('estado') == 'ANULADO' ? 'selected' : '' }}>ANULADO</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-secondary me-1"><i class="bi bi-funnel-fill me-1"></i> Filtrar</button>
                    <a href="{{ route('ordenes-pago.index') }}" class="btn btn-outline-secondary">Limpiar</a>
                </div>
            </form>

            {{-- Tabla de Registros --}}
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle border mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>N° Orden</th>
                            <th>Solicitante</th>
                            <th>Cédula</th>
                            <th>A Favor De</th>
                            <th>Fecha Ingreso</th>
                            <th>Factura N°</th>
                            <th class="text-end">Total</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ordenes as $orden)
                            <tr>
                                <td>
                                    <strong class="text-primary">{{ $orden->numero_orden }}</strong>
                                </td>
                                <td>
                                    <div class="fw-bold">{{ strtoupper($orden->nombre_solicitante) }}</div>
                                    <small class="text-muted">Elaborado por: {{ $orden->elaborado_por }}</small>
                                </td>
                                <td>{{ $orden->cedula_solicitante }}</td>
                                <td>{{ strtoupper($orden->a_favor_de) }}</td>
                                <td>{{ $orden->fecha_ingreso ? $orden->fecha_ingreso->format('d/m/Y') : '' }}</td>
                                <td>
                                    @if($orden->factura_nro)
                                        <span class="badge bg-light text-dark border">{{ $orden->factura_nro }}</span>
                                    @else
                                        <span class="text-muted fs-7">S/N</span>
                                    @endif
                                </td>
                                <td class="text-end fw-bold text-success">
                                    $ {{ number_format($orden->total, 2) }}
                                </td>
                                <td class="text-center">
                                    @switch($orden->estado)
                                        @case('PENDIENTE')
                                            <span class="badge bg-warning text-dark">PENDIENTE</span>
                                            @break
                                        @case('PAGADO')
                                            <span class="badge bg-success">PAGADO</span>
                                            @break
                                        @case('DEVUELTO')
                                            <span class="badge bg-danger">DEVUELTO</span>
                                            @break
                                        @case('ANULADO')
                                            <span class="badge bg-secondary">ANULADO</span>
                                            @break
                                    @endswitch
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        {{-- Botón Ver / Imprimir --}}
                                        <a href="{{ route('ordenes-pago.show', $orden->id) }}" class="btn btn-outline-primary" title="Ver / Imprimir Orden">
                                            <i class="bi bi-printer-fill"></i>
                                        </a>

                                        {{-- Botón Modal Factura (si está pendiente) --}}
                                        @if($orden->estado === 'PENDIENTE')
                                            <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#modalFactura{{ $orden->id }}" title="Registrar Factura de Tesorería">
                                                <i class="bi bi-cash-stack"></i>
                                            </button>
                                        @endif

                                        {{-- Botón Devolver (solo visible para trámites PAGADOS) --}}
@if($orden->estado === 'PAGADO')
    <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#modalDevolver{{ $orden->id }}" title="Sentar Devolución / Observación">
        <i class="bi bi-arrow-return-left"></i>
    </button>
@endif



{{-- Si está PAGADO, permite enviarlo a CALIFICACIÓN --}}
@if($orden->estado === 'PAGADO')
    <form action="{{ route('ordenes-pago.cambiarEstado', $orden->id) }}" method="POST" class="d-inline">
        @csrf
        @method('PUT')
        <input type="hidden" name="nuevo_estado" value="EN_CALIFICACION">
        <button type="submit" class="btn btn-sm btn-outline-info" title="Pasar a Calificación">
            <i class="bi bi-person-workspace"></i>
        </button>
    </form>
@endif

{{-- Si está EN CALIFICACIÓN, permite marcarlo como INSCRITO --}}
@if($orden->estado === 'EN_CALIFICACION')
    <form action="{{ route('ordenes-pago.cambiarEstado', $orden->id) }}" method="POST" class="d-inline">
        @csrf
        @method('PUT')
        <input type="hidden" name="nuevo_estado" value="INSCRITO">
        <button type="submit" class="btn btn-sm btn-outline-success" title="Marcar como Inscrito">
            <i class="bi bi-check-circle-fill"></i>
        </button>
    </form>
@endif


                                    </div>
                                </td>
                            </tr>

                            {{-- Modal para Registrar Número de Factura --}}
                            <div class="modal fade" id="modalFactura{{ $orden->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <form action="{{ route('ordenes-pago.registrarFactura', $orden->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header bg-success text-white">
                                                <h6 class="modal-title font-weight-bold"><i class="bi bi-receipt me-2"></i> Registrar Factura - {{ $orden->numero_orden }}</h6>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="small text-muted mb-3">Ingrese el número de factura emitida por Tesorería Municipal para cambiar el estado a <strong>PAGADO</strong>.</p>
                                                <div class="mb-3">
                                                    <label class="form-label font-weight-bold">Número de Factura:*</label>
                                                    <input type="text" name="factura_nro" class="form-control" required placeholder="Ej. 1681" value="{{ $orden->factura_nro }}">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-sm btn-success">Guardar y Marcar Pagado</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            

{{-- Modal para Sentar Devolución con Observaciones --}}
<div class="modal fade" id="modalDevolver{{ $orden->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('ordenes-pago.devolver', $orden->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header bg-warning text-dark">
                    <h6 class="modal-title font-weight-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i> Devolución de Trámite - {{ $orden->numero_orden }}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Detalle de Observaciones / Motivo de Devolución:*</label>
                        <textarea name="observaciones" class="form-control" rows="3" required placeholder="Visto la documentación ingresada se verifica que no cumple con..."></textarea>
                    </div>

                    <h6 class="text-primary font-weight-bold border-bottom pb-2">Sección: Devuelto Conforme (Recepción)</h6>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label font-weight-bold">Nombre de quien retira:*</label>
                            <input type="text" name="devuelto_nombre" class="form-control" required value="{{ $orden->nombre_solicitante }}">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label font-weight-bold">Cédula:*</label>
                            <input type="text" name="devuelto_cedula" class="form-control" required value="{{ $orden->cedula_solicitante }}">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label font-weight-bold">Fecha Devolución:*</label>
                            <input type="date" name="devuelto_fecha" class="form-control" required value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-sm btn-warning">Registrar Devolución e Imprimir Hoja</button>
                </div>
            </form>
        </div>
    </div>
</div>

                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                    No se encontraron órdenes de pago registradas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            <div class="d-flex justify-content-end mt-3">
                {{ $ordenes->appends(request()->query())->links() }}
            </div>

        </div>
    </div>
</div>
@endsection