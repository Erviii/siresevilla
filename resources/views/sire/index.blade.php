@extends('layouts.base')

@section('title', 'Sistema Sire - Consultas')

@push('styles')
<style>
    .table-info-custom {
        background-color: rgba(13, 202, 240, 0.05);
    }
    /* Quita los bordes excedentes de la tabla dentro del acordeón */
    .accordion-body .table {
        border-bottom: 0;
    }
</style>
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-12">
        
        <!-- Tarjeta del Buscador Central -->
        <div class="card shadow-sm mb-4 mx-auto" style="max-width: 800px;">
            <div class="card-header bg-primary text-white text-center py-3">
                <h3 class="mb-0 fs-4"><i class="bi bi-search me-2"></i>Módulo de Consultas</h3>
            </div>
            <div class="card-body p-4">

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form id="formBuscador" action="" method="GET">
                    <div class="d-flex justify-content-center mb-4">
                        <div class="form-check form-check-inline mx-3">
                            <input class="form-check-input" type="radio" name="tipo_busqueda" id="buscaCedula" value="cedula" 
                                {{ (isset($tipo) && $tipo == 'cedula') ? 'checked' : '' }} onchange="configurarBusqueda('cedula')">
                            <label class="form-check-label fw-semibold text-secondary" for="buscaCedula">🪪 Cédula/RUC</label>
                        </div>
                        <div class="form-check form-check-inline mx-3">
                            <input class="form-check-input" type="radio" name="tipo_busqueda" id="buscaFicha" value="ficha" 
                                {{ (isset($tipo) && $tipo == 'ficha') ? 'checked' : '' }} onchange="configurarBusqueda('ficha')">
                            <label class="form-check-label fw-semibold text-secondary" for="buscaFicha">📁 Ficha</label>
                        </div>
                        <div class="form-check form-check-inline mx-3">
                            <input class="form-check-input" type="radio" name="tipo_busqueda" id="buscaNombre" value="nombre" 
                                {{ (isset($tipo) && $tipo == 'nombre') ? 'checked' : '' }} onchange="configurarBusqueda('nombre')">
                            <label class="form-check-label fw-semibold text-secondary" for="buscaNombre">👤 Nombres y Apellidos</label>
                        </div>
                    </div>

                    <div class="input-group">
                        <input type="text" id="inputBusqueda" name="valor_busqueda" class="form-control form-control-lg fs-6" 
                               placeholder="Seleccione una opción..." value="{{ $valor ?? '' }}" required>
                        <button class="btn btn-primary btn-lg px-4 fs-6" type="submit">
                            <i class="bi bi-search me-2"></i>Consultar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Renderizado de Resultados si existen -->
        @if(isset($resultados))
            @if(count($resultados) > 0)
                
                <div class="alert alert-success mx-auto d-flex flex-wrap justify-content-between align-items-center mb-4 p-3 shadow-sm" style="max-width: 800px;">
                    <div class="my-1">
                        Búsqueda por <strong>{{ $tipo === 'cedula' ? 'Cédula / RUC' : ($tipo === 'ficha' ? 'Número de Ficha' : 'Nombre') }}</strong>: 
                        <span class="badge bg-dark fs-6 ms-1">{{ $valor }}</span>
                    </div>
                    <div class="d-flex align-items-center gap-2 my-1">
                        <span>Total Fichas:</span> <span class="badge bg-primary fs-6 me-2">{{ count($resultados) }}</span>
                        @if($tipo === 'ficha')
                            <a href="{{ route('sire.imprimir.ficha', $valor) }}" target="_blank" class="btn btn-danger btn-sm rounded-pill px-3">
                                <i class="bi bi-file-earmark-pdf me-1"></i> Generar PDF
                            </a>
                            <a href="{{ route('sire.registrar', $valor) }}" class="btn btn-success btn-sm rounded-pill px-3">
                                <i class="bi bi-plus-circle me-1"></i> Registrar Movimiento
                            </a>
                        @endif
                    </div>
                </div>
                
                <!-- Acordeón de Resultados -->
                <div class="accordion mx-auto mb-5" id="accordionResultados" style="max-width: 1250px;">
                    @foreach($resultados as $numeroFicha => $movimientos)
                        <div class="accordion-item mb-3 shadow-sm border rounded overflow-hidden">
                            <h2 class="accordion-header" id="headingFicha-{{ $numeroFicha ?: '0' }}">
                                <button class="accordion-button collapsed bg-white py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFicha-{{ $numeroFicha ?: '0' }}" aria-expanded="false">
                                    <div class="d-flex align-items-center w-100 justify-content-between pe-3">
                                        <span class="text-primary fw-bold fs-5">
                                            <i class="bi bi-folder2-open me-2"></i>Ficha N°: {{ $numeroFicha ?: 'Sin Ficha' }}
                                        </span>
                                        <span class="badge bg-light text-dark border px-3 py-2 fs-7">
                                            {{ count($movimientos) }} acto(s) o movimiento(s)
                                        </span>
                                    </div>
                                </button>
                            </h2>
                            
                            <div id="collapseFicha-{{ $numeroFicha ?: '0' }}" class="accordion-collapse collapse" aria-labelledby="headingFicha-{{ $numeroFicha ?: '0' }}">
                                <div class="accordion-body p-0 bg-white border-top">
                                    <div class="table-responsive">
                                        <table class="table table-striped align-middle mb-0">
                                            <thead class="table-dark text-center small text-uppercase font-monospace">
                                                <tr>
                                                    <th style="width: 10%;">N° Rep.</th>
                                                    <th style="width: 10%;">N° Ins.</th>
                                                    <th style="width: 15%;">Fecha Inscripción</th>
                                                    <th style="width: 30%;">Tipo de Acto / Libro</th>
                                                    <th>Intervinientes en el Acto</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($movimientos as $claveMovimiento => $intervinientes)
                                                    @php 
                                                        $infoMovimiento = $intervinientes->first(); 
                                                    @endphp
                                                    <tr>
                                                        <td class="text-center fw-bold text-secondary font-monospace">{{ $infoMovimiento->num_repertorio }}</td>
                                                        <td class="text-center fw-bold text-secondary font-monospace">{{ $infoMovimiento->num_inscripcion }}</td>
                                                        <td class="text-center small text-muted">
                                                            {{ $infoMovimiento->fecha_inscripcion ? \Carbon\Carbon::parse($infoMovimiento->fecha_inscripcion)->format('d/m/Y H:i') : 'N/A' }}
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-info text-dark text-wrap text-start mb-1 w-100 d-block p-2 fs-7 fw-semibold">
                                                                {{ $infoMovimiento->tipo_acto }}
                                                            </span>
                                                            <small class="text-muted d-block ps-1">
                                                                <strong>Libro:</strong> {{ $infoMovimiento->libro }}
                                                            </small>
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-sm btn-outline-primary w-100 text-start d-flex justify-content-between align-items-center" type="button" data-bs-toggle="collapse" data-bs-target="#intervinientes-{{ $numeroFicha }}-{{ $claveMovimiento }}" aria-expanded="false">
                                                                <span><i class="bi bi-people me-2"></i>Ver {{ count($intervinientes) }} persona(s)</span>
                                                                <i class="bi bi-chevron-down small"></i>
                                                            </button>
                                                            
                                                            <div class="collapse mt-2" id="intervinientes-{{ $numeroFicha }}-{{ $claveMovimiento }}">
                                                                <ul class="list-group shadow-sm">
                                                                    @foreach($intervinientes as $persona)
                                                                        <li class="list-group-item p-2 small d-flex justify-content-between align-items-center bg-light">
                                                                            <div>
                                                                                <i class="bi bi-person me-1 text-muted"></i>
                                                                                <span class="fw-semibold text-dark">{{ $persona->nombre_cliente }}</span>
                                                                            </div>
                                                                            <div>
                                                                                <span class="badge bg-secondary font-monospace">🪪 {{ $persona->cedula_ruc ?: 'S/C' }}</span>
                                                                            </div>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            @endif
        @endif

    </div>
</div>
@endsection

@push('scripts')
<script>
function configurarBusqueda(tipo) {
    const form = document.getElementById('formBuscador');
    const input = document.getElementById('inputBusqueda');
    
    if (tipo === 'cedula') {
        form.action = "{{ route('sire.buscar.cedula') }}";
        input.type = "number";
        input.placeholder = "Escriba el número de cédula o RUC...";
    } else if (tipo === 'ficha') {
        form.action = "{{ route('sire.buscar.ficha') }}";
        input.type = "number";
        input.placeholder = "Escriba el número de ficha...";
    } else if (tipo === 'nombre') {
        form.action = "{{ route('sire.buscar.nombre') }}";
        input.type = "text";
        input.placeholder = "Escriba el nombre del cliente...";
    }
}

// Asegura que al cargar la página el formulario mantenga la configuración del tipo seleccionado
document.addEventListener("DOMContentLoaded", function() {
    const tipoActual = "{{ $tipo ?? 'cedula' }}";
    configurarBusqueda(tipoActual);
});
</script>
@endpush