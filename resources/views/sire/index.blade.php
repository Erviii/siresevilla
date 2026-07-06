<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Sire - Consultas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table-info-custom {
            background-color: rgba(13, 202, 240, 0.05);
        }
        /* Un pequeño ajuste para quitar los bordes de la tabla dentro del acordeón y se vea más limpio */
        .accordion-body .table {
            border-bottom: 0;
        }
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container-fluid px-4">
        <a class="navbar-brand fw-bold" href="#">🏛️ Sistema de Información Registral</a>
        
        <div class="d-flex align-items-center">
            @auth
                <span class="text-white me-3 fs-6">
                    👤 Bienvenido, <strong>{{ auth()->user()->usuanombre }}</strong>
                </span>
                
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">
                        🚪 Cerrar Sesión
                    </button>
                </form>
            @endauth
        </div>
    </div>
</nav>
<div class="container-fluid mt-4 px-4"> <div class="row justify-content-center">
        <div class="col-12">
            
            <div class="card shadow-sm mb-4 mx-auto" style="max-width: 800px;">
                <div class="card-header bg-primary text-white text-center">
                    <h3 class="mb-0">🏛️ Módulo de Consultas</h3>
                </div>
                <div class="card-body p-4">

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

                    <form id="formBuscador" action="" method="GET">
    <div class="d-flex justify-content-center mb-3">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="tipo_busqueda" id="buscaCedula" value="cedula" 
                {{ (isset($tipo) && $tipo == 'cedula') ? 'checked' : '' }} onchange="configurarBusqueda('cedula')">
            <label class="form-check-label" for="buscaCedula">Cédula/RUC</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="tipo_busqueda" id="buscaFicha" value="ficha" 
                {{ (isset($tipo) && $tipo == 'ficha') ? 'checked' : '' }} onchange="configurarBusqueda('ficha')">
            <label class="form-check-label" for="buscaFicha">Ficha</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="tipo_busqueda" id="buscaNombre" value="nombre" 
                {{ (isset($tipo) && $tipo == 'nombre') ? 'checked' : '' }} onchange="configurarBusqueda('nombre')">
            <label class="form-check-label" for="buscaNombre">Nombres y Apellidos</label>
        </div>
    </div>

    <div class="input-group">
        <input type="text" id="inputBusqueda" name="valor_busqueda" class="form-control form-control-lg" 
               placeholder="Seleccione una opción..." value="{{ $valor ?? '' }}" required>
        <button class="btn btn-primary btn-lg" type="submit">🔍 Consultar</button>
    </div>
</form>
                </div>
            </div>

            @if(isset($resultados))
                @if(count($resultados) > 0)
                    
                    <div class="alert alert-success mx-auto d-flex justify-content-between align-items-center" style="max-width: 800px;">
                        <div>
                            Resultados para la búsqueda por <strong>{{ $tipo === 'cedula' ? 'Cédula / RUC' : ($tipo === 'ficha' ? 'Número de Ficha' : 'Nombre') }}</strong>: 
                            <span class="badge bg-dark fs-6 ms-1">{{ $valor }}</span>
                        </div>
                        <div>
                            Total Fichas: <span class="badge bg-primary fs-6">{{ count($resultados) }}</span>
                            @if($tipo === 'ficha')
                                <a href="{{ route('sire.imprimir.ficha', $valor) }}" target="_blank" class="btn btn-danger btn-sm ms-3">
                                    🖨️ Generar PDF
                                </a>
                            @endif
                        </div>
                    </div>
                    
                    <div class="accordion mx-auto" id="accordionResultados" style="max-width: 1250px;">
    
    @foreach($resultados as $numeroFicha => $movimientos)
        
        <div class="accordion-item mb-4 shadow-sm border rounded">
            <h2 class="accordion-header" id="headingFicha-{{ $numeroFicha ?: '0' }}">
                <button class="accordion-button collapsed bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFicha-{{ $numeroFicha ?: '0' }}" aria-expanded="false">
                    <strong class="text-primary fs-5">
                        📁 Ficha N°: {{ $numeroFicha ?: 'Sin Ficha' }}
                    </strong>
                    <span class="badge bg-secondary ms-4">{{ count($movimientos) }} acto(s) o movimiento(s) único(s)</span>
                </button>
            </h2>
            
            <div id="collapseFicha-{{ $numeroFicha ?: '0' }}" class="accordion-collapse collapse" aria-labelledby="headingFicha-{{ $numeroFicha ?: '0' }}">
                <div class="accordion-body p-3 bg-white">
                    
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
                            <thead class="table-dark text-center small">
                                <tr>
                                    <th style="width: 10%;">N° Rep.</th>
                                    <th style="width: 10%;">N° Ins.</th>
                                    <th style="width: 15%;">Fecha Inscripción</th>
                                    <th style="width: 25%;">Tipo de Acto / Libro</th>
                                    <th>Intervinientes en el Acto</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                @foreach($movimientos as $claveMovimiento => $intervinientes)
                                    @php 
                                        // Tomamos el primer registro del grupo para extraer los datos generales del movimiento
                                        $infoMovimiento = $intervinientes->first(); 
                                    @endphp
                                    <tr>
                                        <td class="text-center fw-bold text-secondary">{{ $infoMovimiento->num_repertorio }}</td>
                                        <td class="text-center fw-bold text-secondary">{{ $infoMovimiento->num_inscripcion }}</td>
                                        
                                        <td class="text-center small">
                                            {{ $infoMovimiento->fecha_inscripcion ? \Carbon\Carbon::parse($infoMovimiento->fecha_inscripcion)->format('d/m/Y H:i') : 'N/A' }}
                                        </td>
                                        
                                        <td>
                                            <span class="badge bg-info text-dark text-wrap text-start mb-1 w-100 d-block fs-6">
                                                {{ $infoMovimiento->tipo_acto }}
                                            </span>
                                            <small class="text-muted d-block"><strong>Libro:</strong> {{ $infoMovimiento->libro }}</small>
                                        </td>
                                        
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary w-100 text-start d-flex justify-content-between align-items-center" type="button" data-bs-toggle="collapse" data-bs-target="#intervinientes-{{ $numeroFicha }}-{{ $claveMovimiento }}" aria-expanded="false">
                                                <span>👥 Ver {{ count($intervinientes) }} persona(s)</span>
                                                <i class="bi bi-chevron-down small"></i>
                                            </button>
                                            
                                            <div class="collapse mt-2" id="intervinientes-{{ $numeroFicha }}-{{ $claveMovimiento }}">
                                                <ul class="list-group shadow-sm">
                                                    @foreach($intervinientes as $persona)
                                                        <li class="list-group-item p-2 small d-flex justify-content-between align-items-center bg-light">
                                                            <div>
                                                                <span class="fw-semibold text-dark">{{ $persona->nombre_cliente }}</span>
                                                            </div>
                                                            <div>
                                                                <span class="badge bg-secondary">🪪 {{ $persona->cedula_ruc ?: 'S/C' }}</span>
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
</div>

<script>
function configurarBusqueda(tipo) {
    const form = document.getElementById('formBuscador');
    const input = document.getElementById('inputBusqueda');
    
    // Asignamos las rutas usando las directivas de Laravel
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

// Esto asegura que al cargar la página, la ruta del formulario esté correcta
document.addEventListener("DOMContentLoaded", function() {
    const tipoActual = "{{ $tipo ?? 'cedula' }}";
    configurarBusqueda(tipoActual);
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>