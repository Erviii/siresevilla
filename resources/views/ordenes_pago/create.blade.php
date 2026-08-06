@extends('layouts.base')

@section('title', 'Nueva Orden de Pago')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-file-earmark-plus-fill me-2"></i> Nueva Solicitud / Orden de Pago</h5>
            <span class="badge bg-light text-dark fs-6">{{ $nuevoNumero }}</span>
        </div>
        <div class="card-body">

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Por favor corrige los siguientes errores:</strong>
                    <ul class="mb-0 mt-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('ordenes-pago.store') }}" method="POST">
                @csrf

                {{-- SECCIÓN 1: DATOS DEL SOLICITANTE --}}
                <h6 class="text-primary font-weight-bold mb-3 border-bottom pb-2">
                    <i class="bi bi-person-fill me-1"></i> Datos del Solicitante
                </h6>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label font-weight-bold">Nombre del Solicitante:*</label>
                        <input type="text" name="nombre_solicitante" class="form-control" required placeholder="Ej: MARISOL SANMARTIN" value="{{ old('nombre_solicitante') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label font-weight-bold">Cédula del Solicitante:*</label>
                        <input type="text" name="cedula_solicitante" class="form-control" required placeholder="Ej: 0105040208" value="{{ old('cedula_solicitante') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Correo Notificación / Teléfono:</label>
                        <input type="text" name="correo_notificacion" class="form-control" placeholder="Ej: 0994186218" value="{{ old('correo_notificacion') }}">
                    </div>
                </div>

                {{-- SECCIÓN 2: DATOS DEL ACTO Y/O CONTRATO --}}
                <h6 class="text-primary font-weight-bold mb-3 border-bottom pb-2 mt-4">
                    <i class="bi bi-file-text-fill me-1"></i> Datos del Acto y/o Contrato
                </h6>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label font-weight-bold">Otorgado por:*</label>
                        <input type="text" name="otorgado_por" class="form-control" required placeholder="Ej: JUEZ DE COACTIVAS DE GOBIERNO MUNICIPAL DE CANTON MORONA" value="{{ old('otorgado_por') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label font-weight-bold">A favor de:*</label>
                        <input type="text" name="a_favor_de" class="form-control" required placeholder="Ej: AVILA ROMAN LUIS GERARDO" value="{{ old('a_favor_de') }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label font-weight-bold">Fecha Ingreso:*</label>
                        <input type="date" name="fecha_ingreso" class="form-control" required value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label font-weight-bold">Cuantía (Texto):</label>
                        <input type="text" name="cuantia_texto" id="cuantia_texto" class="form-control" placeholder="Ej: INDETERMINADA o $ 10.000,00" value="INDETERMINADA">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label font-weight-bold">Cuantía (Valor Númerico para Cálculo):</label>
                        <input type="number" step="0.01" name="cuantia_monto" id="cuantia_monto" class="form-control" value="0.00">
                    </div>
                </div>

                {{-- SECCIÓN 3: CÁLCULO DE ARANCELES --}}
                <h6 class="text-primary font-weight-bold mb-3 border-bottom pb-2 mt-4">
                    <i class="bi bi-calculator-fill me-1"></i> Selección del Acto / Rubro Registral
                </h6>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label font-weight-bold">Tipo de Acto Registral:*</label>
                        <select name="tipo_acto" id="tipo_acto" class="form-select" required>
                            <option value="">-- Seleccionar Servicio --</option>
                            @foreach($servicios as $servicio)
                                <option value="{{ $servicio->codigo }}">
                                    {{ $servicio->nombre }} ({{ $servicio->articulo_ordenanza }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label font-weight-bold">N° Predios / Lotes:</label>
                        <input type="number" name="cantidad_predios" id="cantidad_predios" class="form-control" value="1" min="1">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label font-weight-bold">Exoneración (50%):</label>
                        <select name="tipo_exoneracion" id="tipo_exoneracion" class="form-select">
                            <option value="NINGUNA">Sin Exoneración</option>
                            <option value="TERCERA_EDAD">Tercera Edad (≥ 65)</option>
                            <option value="DISCAPACIDAD">Discapacidad</option>
                        </select>
                    </div>
                </div>

                {{-- RESUMEN DE TOTALES --}}
                <div class="card bg-light border my-4">
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-4">
                                <span class="text-muted d-block">Subtotal:</span>
                                <h4 class="text-dark fw-bold">$ <span id="txt_subtotal">0.00</span></h4>
                            </div>
                            <div class="col-md-4">
                                <span class="text-muted d-block">Descuento:</span>
                                <h4 class="text-danger fw-bold">-$ <span id="txt_descuento">0.00</span></h4>
                            </div>
                            <div class="col-md-4">
                                <span class="text-muted d-block">TOTAL A PAGAR:</span>
                                <h3 class="text-success fw-bold">$ <span id="txt_total">0.00</span></h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <a href="{{ route('ordenes-pago.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Guardar y Generar Documento</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script JS para actualización automática de totales --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tipoActo = document.getElementById('tipo_acto');
    const cuantiaMonto = document.getElementById('cuantia_monto');
    const predios = document.getElementById('cantidad_predios');
    const exoneracion = document.getElementById('tipo_exoneracion');

    function calcular() {
        if (!tipoActo.value) return;

        fetch(`{{ route('ordenes-pago.calcularAjax') }}?tipo_acto=${tipoActo.value}&cuantia=${cuantiaMonto.value}&cantidad_predios=${predios.value}&tipo_exoneracion=${exoneracion.value}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('txt_subtotal').innerText = parseFloat(data.subtotal).toFixed(2);
                document.getElementById('txt_descuento').innerText = parseFloat(data.descuento).toFixed(2);
                document.getElementById('txt_total').innerText = parseFloat(data.total).toFixed(2);
            });
    }

    tipoActo.addEventListener('change', calcular);
    cuantiaMonto.addEventListener('input', calcular);
    predios.addEventListener('input', calcular);
    exoneracion.addEventListener('change', calcular);
});
</script>
@endsection