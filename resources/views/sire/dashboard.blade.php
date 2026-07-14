@extends('layouts.base')
@section('title', 'Dashboard - SIRE')
@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold text-secondary">Panel de Control General</h2>
            <p class="text-muted">Resumen de operaciones y accesos directos del sistema.</p>
            
        </div>
    </div>
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card card-stat bg-white p-3 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <small class="text-muted text-uppercase fw-bold" style="font-size: 0.75rem;">Fichas Totales</small>
                    <h3 class="fw-bold mb-0 text-dark">{{ $cantFichas ?? 0 }}</h3>
                </div>
                <div class="bg-primary bg-opacity-10 p-3 rounded text-primary fs-3">
                    <i class="bi bi-folder2-open"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stat bg-white p-3 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <small class="text-muted text-uppercase fw-bold" style="font-size: 0.75rem;">Movimientos Hoy</small>
                    <h3 class="fw-bold mb-0 text-dark">{{ $movimientosHoy ?? 0 }}</h3>
                </div>
                <div class="bg-success bg-opacity-10 p-3 rounded text-success fs-3">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stat bg-white p-3 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <small class="text-muted text-uppercase fw-bold" style="font-size: 0.75rem;">Usuarios del Sistema</small>
                    <h3 class="fw-bold mb-0 text-dark">{{ $cantClientes ?? 0 }}</h3>
                </div>
                <div class="bg-info bg-opacity-10 p-3 rounded text-info fs-3">
                    <i class="bi bi-people"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stat bg-white p-3 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <small class="text-muted text-uppercase fw-bold" style="font-size: 0.75rem;">Libros Activos</small>
                    <h3 class="fw-bold mb-0 text-dark">{{ $cantLibros ?? 0 }}</h3>
                </div>
                <div class="bg-warning bg-opacity-10 p-3 rounded text-warning fs-3">
                    <i class="bi bi-book"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        
        <div class="col-lg-8">
            
            <div class="card border-0 shadow-sm p-4 mb-4">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-search text-primary me-2"></i>Buscar Ficha para Movimiento</h5>
                <p class="text-muted small">Ingrese el número de ficha jurídica o de propiedad para comenzar a registrar una inscripción o nota marginal.</p>
                
                <form action="#" method="GET" id="formBuscarFicha" onsubmit="redirigirFicha(event)">
                    <div class="input-group mb-1">
                        <span class="input-group-text bg-light"><i class="bi bi-hash"></i></span>
                        <input type="number" id="inputFichaNum" class="form-control form-control-lg" placeholder="Ej: 45892" required>
                        <button class="btn btn-primary px-4" type="submit">Buscar Ficha</button>
                    </div>
                </form>
            </div>

            <div class="card border-0 shadow-sm p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-clock-history text-secondary me-2"></i>Últimos Movimientos Registrados</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Repertorio</th>
                                <th>Inscripción</th>
                                <th>Fecha</th>
                                <th>Libro</th>
                                <th>Tipo de Acto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ultimosMovimientos ?? [] as $mov)
                                <tr>
                                    <td class="fw-bold text-primary">#{{ $mov->movinumrep }}</td>
                                    <td>{{ $mov->movinumins }}</td>
                                    <td>{{ date('d/m/Y', strtotime($mov->movifecins)) }}</td>
                                    <td><span class="badge bg-secondary bg-opacity-10 text-secondary border px-2 py-1">{{ $mov->librnombre }}</span></td>
                                    <td>{{ $mov->actonombre }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">No se han registrado movimientos el día de hoy.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm p-4 sticky-top" style="top: 20px; z-index: 10;">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-list-task text-primary me-2"></i>Menú de Opciones</h5>
                <p class="text-muted small mb-4">Accesos directos globales a las principales operaciones de la aplicación.</p>
                
                <div class="d-grid gap-3">
                    <a href="{{ route('sire.index') }}" class="btn btn-white btn-action p-3 text-start d-flex align-items-center rounded shadow-sm text-decoration-none">
                        <div class="bg-primary bg-opacity-10 p-2 rounded text-primary me-3">
                            <i class="bi bi-file-earmark-text fs-4"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark">Modulo del Busqueda</div>
                            <small class="text-muted d-block" style="font-size: 0.75rem;">Consultar y exportar la bitácora global</small>
                        </div>
                    </a>

                    <a href="{{ route('sire.buscar.ficha') }}" class="btn btn-white btn-action p-3 text-start d-flex align-items-center rounded shadow-sm text-decoration-none">
                        <div class="bg-secondary bg-opacity-10 p-2 rounded text-secondary me-3">
                            <i class="bi bi-plus-circle fs-4"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark">Registrar Nuevo Movimiento</div>
                            <small class="text-muted d-block" style="font-size: 0.75rem;">Registrar Movimientos</small>
                        </div>
                    </a>

                    <a href="#" class="btn btn-white btn-action p-3 text-start d-flex align-items-center rounded shadow-sm text-decoration-none">
                        <div class="bg-dark bg-opacity-10 p-2 rounded text-dark me-3">
                            <i class="bi bi-gear fs-4"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark">Configuración</div>
                            <small class="text-muted d-block" style="font-size: 0.75rem;">Administración de libros y catálogos</small>
                        </div>
                    </a>
                </div>
            </div>
        </div>
@endsection

@push('scripts')
<script>
    function redirigirFicha(event) {
        event.preventDefault();
        const fichaNum = document.getElementById('inputFichaNum').value;
        if(fichaNum) {
            window.location.href = `/movimiento/registrar/${fichaNum}`;
        }
    }
</script>
@endpush