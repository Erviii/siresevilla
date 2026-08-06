<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Estado de Trámite</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f6f9; }
        .timeline-step { text-align: center; flex: 1; position: relative; }
        .icon-box { width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 1.2rem; background: #e9ecef; color: #6c757d; border: 3px solid #dee2e6; }
        .timeline-step.completed .icon-box { background: #198754; color: #fff; border-color: #198754; }
        .timeline-step.active .icon-box { background: #0d6efd; color: #fff; border-color: #0d6efd; box-shadow: 0 0 10px rgba(13,110,253,0.4); }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-9 text-center mb-4">
            <h3 class="fw-bold text-primary"><i class="bi bi-search me-2"></i> Registro de la Propiedad y Mercantil</h3>
            <p class="text-muted">Consulte en tiempo real el estado de su Orden de Pago o Trámite Registral</p>
        </div>

        {{-- FORMULARIO BÚSQUEDA --}}
        <div class="col-md-8 mb-4">
            <div class="card border-0 shadow-sm p-3">
                <form action="{{ route('ordenes-pago.consulta-publica') }}" method="GET" class="row g-2">
                    <div class="col-8 col-md-9">
                        <input type="text" name="criterio" class="form-control form-control-lg" required placeholder="N° Orden (Ej: OP-2026-00001) o Cédula..." value="{{ request('criterio') }}">
                    </div>
                    <div class="col-4 col-md-3">
                        <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold">
                            <i class="bi bi-search"></i> Buscar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- RESULTADO --}}
        @if($buscado)
            @if($orden)
                <div class="col-md-10">
                    <div class="card border-0 shadow-lg rounded-3">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
                            <h5 class="mb-0 fw-bold"><i class="bi bi-file-earmark-check me-2"></i> Orden N° {{ $orden->numero_orden }}</h5>
                            <span class="badge bg-light text-dark fs-6">{{ $orden->estado }}</span>
                        </div>
                        <div class="card-body p-4">
                            <div class="row mb-4 border-bottom pb-3">
                                <div class="col-md-4">
                                    <small class="text-muted d-block">Solicitante:</small>
                                    <strong>{{ strtoupper($orden->nombre_solicitante) }}</strong>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted d-block">A Favor De:</small>
                                    <strong>{{ strtoupper($orden->a_favor_de) }}</strong>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted d-block">Fecha Ingreso:</small>
                                    <strong>{{ $orden->fecha_ingreso ? $orden->fecha_ingreso->format('d/m/Y') : '' }}</strong>
                                </div>
                            </div>

                            <h6 class="fw-bold text-center mb-4 text-primary">Estado del Avance</h6>

                            @if($orden->estado === 'DEVUELTO')
                                <div class="alert alert-danger p-3 text-center">
                                    <i class="bi bi-exclamation-octagon-fill fs-3 d-block mb-1"></i>
                                    <strong>Trámite Devuelto con Observaciones</strong>
                                    <p class="mb-0 mt-1 small">{{ $orden->observaciones ?? 'Acérquese a la institución para revisar las observaciones.' }}</p>
                                </div>
                            @else
                                <div class="d-flex justify-content-between mb-2">
                                    {{-- 1. Solicitud --}}
                                    <div class="timeline-step completed">
                                        <div class="icon-box"><i class="bi bi-file-earmark-text"></i></div>
                                        <small class="fw-bold d-block">1. Solicitud</small>
                                        <span class="badge bg-success small">Ingresado</span>
                                    </div>

                                    {{-- 2. Pago --}}
                                    <div class="timeline-step {{ in_array($orden->estado, ['PAGADO', 'EN_CALIFICACION', 'INSCRITO']) ? 'completed' : 'active' }}">
                                        <div class="icon-box"><i class="bi bi-cash-stack"></i></div>
                                        <small class="fw-bold d-block">2. Tesorería</small>
                                        @if($orden->factura_nro)
                                            <span class="badge bg-success small">Factura: {{ $orden->factura_nro }}</span>
                                        @else
                                            <span class="badge bg-warning text-dark small">Pendiente</span>
                                        @endif
                                    </div>

                                    {{-- 3. Calificación --}}
                                    <div class="timeline-step {{ in_array($orden->estado, ['EN_CALIFICACION', 'INSCRITO']) ? ($orden->estado === 'INSCRITO' ? 'completed' : 'active') : '' }}">
                                        <div class="icon-box"><i class="bi bi-person-workspace"></i></div>
                                        <small class="fw-bold d-block">3. Calificación</small>
                                        @if($orden->estado === 'EN_CALIFICACION')
                                            <span class="badge bg-primary small">En Proceso</span>
                                        @elseif($orden->estado === 'INSCRITO')
                                            <span class="badge bg-success small">Completado</span>
                                        @else
                                            <span class="badge bg-secondary small">En espera</span>
                                        @endif
                                    </div>

                                    {{-- 4. Inscrito --}}
                                    <div class="timeline-step {{ $orden->estado === 'INSCRITO' ? 'completed' : '' }}">
                                        <div class="icon-box"><i class="bi bi-check-circle-fill"></i></div>
                                        <small class="fw-bold d-block">4. Entrega</small>
                                        @if($orden->estado === 'INSCRITO')
                                            <span class="badge bg-success small">Listo p/ Retiro</span>
                                        @else
                                            <span class="badge bg-secondary small">Pendiente</span>
                                        @endif
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            @else
                <div class="col-md-8 text-center py-4">
                    <div class="alert alert-warning border-0 shadow-sm">
                        <i class="bi bi-exclamation-triangle fs-2 d-block mb-2"></i>
                        No se encontró ningún trámite registrado con los datos ingresados.
                    </div>
                </div>
            @endif
        @endif

    </div>
</div>

</body>
</html>