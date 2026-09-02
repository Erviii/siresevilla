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
    <!-- Encabezado -->
    <h5 class="fw-bold text-dark mb-1">
        <i class="bi bi-search text-primary me-2"></i>Buscar Ficha para Movimiento
    </h5>
    <p class="text-muted small mb-3">
        Ingrese el número de ficha jurídica para registrar una inscripción o perturar un nuevo predio.
    </p>

    <!-- Contenedor alineado lado a lado -->
    <div class="row g-2 align-items-center">
        <!-- Input de Búsqueda (Ocupa el espacio principal) -->
        <div class="col-12 col-md-8 col-lg-9">
            <form action="#" method="GET" id="formBuscarFicha" onsubmit="redirigirFicha(event)" class="m-0">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted">
                        <i class="bi bi-hash"></i>
                    </span>
                    <input type="number" id="inputFichaNum" class="form-control form-control-lg border-start-0" placeholder="Ej: 45892" required>
                    @can('crear-movimientos')
                    <button class="btn btn-primary px-4 fw-semibold" type="submit">
                        <i class="bi bi-search me-1"></i> Buscar Ficha
                    </button>
                    @endcan
                </div>
            </form>
        </div>

        <!-- Botón de Apertura (Alineado a la derecha) -->
        <div class="col-12 col-md-4 col-lg-3 text-md-end">
            @can('crear-fichas')
            <a href="{{ route('fichas.create') }}" class="btn btn-success btn-lg fw-semibold shadow-sm w-100 text-nowrap">
                <i class="bi bi-plus-circle me-1"></i> Aperturar Ficha
            </a>
            @endcan
        </div>
    </div>
</div>

            

            <div class="card border-0 shadow-sm p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-clock-history text-secondary me-2"></i>Últimos Movimientos Registrados</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Ficha</th>
                                <th>Repertorio</th>
                                <th>Inscripción</th>
                                <th>Fecha</th>
                                <th>Libro</th>
                                <th>Tipo de Acto</th>
                                <th>Acciones</th>
                                
                                
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ultimosMovimientos ?? [] as $mov)

                            @php 
        $fecInsParam = $mov->movifecins 
            ? \Carbon\Carbon::parse($mov->movifecins)->format('Y-m-d') 
            : date('Y-m-d');
    @endphp
                                <tr>
                                    <td class="fw-bold text-primary"> SDB-{{ $mov->num_ficha }}</td>
                                    <td>{{ $mov->movinumrep }}</td>
                                    <td>{{ $mov->movinumins }}</td>
                                    <td>{{ date('d/m/Y', strtotime($mov->movifecins)) }}</td>
                                    <td><span class="badge bg-secondary bg-opacity-10 text-secondary border px-2 py-1">{{ $mov->librnombre }}</span></td>
                                    <td>{{ $mov->actonombre }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group" aria-label="Reportes PDF">
                        {{-- Botón Razón de Inscripción --}}

                          
            
                        @can('imprimir-razon')
                        <a href="{{ route('reportes.razon.pdf', [
                                'numrep' => $mov->movinumrep,
                                'fecins' => $fecInsParam,
                                'numins' => $mov->movinumins
                            ]) }}" 
                           target="_blank" 
                           class="btn btn-outline-primary" 
                           title="Ver Razón de Inscripción (PDF)">
                            <i class="bi bi-file-earmark-pdf-fill text-danger me-1"></i> Razón
                        </a>
                        @endcan

                        {{-- Botón Acta de Inscripción --}}
                        @can('imprimir-acta')
                        <a href="{{ route('reportes.acta.pdf', [
                                'numrep' => $mov->movinumrep,
                                'fecins' => $fecInsParam,
                                'numins' => $mov->movinumins
                            ]) }}" 
                           target="_blank" 
                           class="btn btn-outline-dark" 
                           title="Ver Acta de Inscripción (PDF)">
                            <i class="bi bi-journal-text me-1"></i> Acta
                        </a>
                        @endcan

                        {{-- Botón Imprimir Ficha (Si la ficha existe) --}}
                        @can('imprimir-ficha')
                        @if(!empty($mov->num_ficha))
                            <a href="{{ route('sire.imprimir.ficha', $mov->num_ficha) }}" 
                               target="_blank" 
                               class="btn btn-outline-success" 
                               title="Ver Ficha Registral (PDF)">
                                <i class="bi bi-file-earmark-text-fill text-danger me-1"></i> Ficha
                            </a>
                        @endif
                        @endcan
                    </div>



                                    </td>
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
                
                @can('ver-fichas')
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
@endcan
 @can('crear-fichas')
                    <a href="{{ route('fichas.create') }}" class="btn btn-white btn-action p-3 text-start d-flex align-items-center rounded shadow-sm text-decoration-none">
                        <div class="bg-secondary bg-opacity-10 p-2 rounded text-secondary me-3">
                            <i class="bi bi-plus-circle fs-4"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark">Apertura Ficha Registral</div>
                            <small class="text-muted d-block" style="font-size: 0.75rem;">Aperturar Ficha</small>
                        </div>
                    </a>
@endcan
                    @can('configurar-sistema')
                    <a href="{{ route('configuracion.index') }}" class="btn btn-white btn-action p-3 text-start d-flex align-items-center rounded shadow-sm text-decoration-none">
                        <div class="bg-dark bg-opacity-10 p-2 rounded text-dark me-3">
                            <i class="bi bi-gear fs-4"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark">Configuración</div>
                            <small class="text-muted d-block" style="font-size: 0.75rem;">Configuracion del Sistema</small>
                        </div>
                    </a>
                    @endcan
@can('gestionar-pagos')
                   <a href="{{ route('ordenes-pago.index') }}" class="btn bg-white border border-primary-subtle border-2 btn-action p-3 text-start d-flex align-items-center rounded-3 shadow-sm text-decoration-none">
    <div class="bg-primary text-white p-3 rounded-3 me-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 48px; height: 48px;">
        <i class="bi bi-receipt-cutoff fs-3"></i>
    </div>
    <div>
        <div class="fw-bold text-dark fs-6 mb-0">Órdenes de Pago</div>
        <small class="text-primary fw-semibold d-block" style="font-size: 0.78rem;">Solicitudes y Liquidación de Aranceles</small>
    </div>
</a>
@endcan
@can('administrar-usuarios')
<a href="{{ route('usuarios.index') }}" class="btn bg-white border border-primary-subtle border-2 btn-action p-3 text-start d-flex align-items-center rounded-3 shadow-sm text-decoration-none">
    <div class="bg-primary text-white p-3 rounded-3 me-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 48px; height: 48px;">
        <i class="bi bi-people-fill fs-3"></i>
    </div>
    <div>
        <div class="fw-bold text-dark fs-6 mb-0">Gestión de Usuarios</div>
        <small class="text-primary fw-semibold d-block" style="font-size: 0.78rem;">Administración de Perfiles y Roles</small>
    </div>
</a>
@endcan

@can('administrar-usuarios')
<a href="{{ route('roles.index') }}" class="btn bg-white border border-primary-subtle border-2 btn-action p-3 text-start d-flex align-items-center rounded-3 shadow-sm text-decoration-none h-100">
            <div class="bg-primary text-white p-3 rounded-3 me-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 48px; height: 48px;">
                <i class="bi bi-shield-lock-fill fs-3"></i>
            </div>
            <div>
                <div class="fw-bold text-dark fs-6 mb-0">Matriz de Permisos</div>
                <small class="text-primary fw-semibold d-block" style="font-size: 0.78rem;">Configuración de Accesos por Rol</small>
            </div>
        </a>
        @endcan
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