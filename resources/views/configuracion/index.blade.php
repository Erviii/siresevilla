@extends('layouts.base')

@section('title', 'Configuración del Sistema')

@section('content')
<div class="container-fluid py-4">

    {{-- Mensajes de Éxito --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Errores de Validación --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Ocurrieron algunos errores:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">

        {{-- COLUMNA IZQUIERDA: Configuración General --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white font-weight-bold">
                    <i class="bi bi-gear-fill me-2"></i> Configuración del Registro
                </div>
                <div class="card-body">
                    <form action="{{ route('configuracion.updateGeneral') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="nombre_registro" class="form-label font-weight-bold">Nombre de la Institución / Registro:</label>
                            <input type="text" class="form-control" id="nombre_registro" name="nombre_registro" 
                                   value="{{ old('nombre_registro', $configuracion->nombre_registro ?? '') }}" required placeholder="Ej. Registro de la Propiedad de Sevilla Don Bosco">
                        </div>

                        <div class="mb-3">
                            <label for="direccion" class="form-label font-weight-bold">Dirección:</label>
                            <input type="text" class="form-control" id="direccion" name="direccion" 
                                   value="{{ old('direccion', $configuracion->direccion ?? '') }}" required placeholder="Ej. Av. Principal y Calle 10">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="periodo_activo" class="form-label font-weight-bold">Periodo Activo (Año):</label>
                                <input type="text" class="form-control" id="periodo_activo" name="periodo_activo" 
                                       value="{{ old('periodo_activo', $configuracion->periodo_activo ?? date('Y')) }}" required placeholder="Ej. 2026">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="telefono" class="form-label font-weight-bold">Teléfono de Contacto:</label>
                                <input type="text" class="form-control" id="telefono" name="telefono" 
                                       value="{{ old('telefono', $configuracion->telefono ?? '') }}" placeholder="Ej. 072-000-000">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label font-weight-bold">Correo Electrónico Institucional:</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="{{ old('email', $configuracion->email ?? '') }}" placeholder="contacto@registro.gob.ec">
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> Guardar Configuración
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- COLUMNA DERECHA: Registrador Actual y Cambio --}}
        <div class="col-lg-6 mb-4">
            {{-- Registrador Actual --}}
            <div class="card shadow-sm border-0 mb-4 border-start border-4 border-info">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <span class="font-weight-bold text-dark"><i class="bi bi-person-badge-fill text-info me-2"></i> Registrador(a) Actual</span>
                    @if($registradorActual)
                        <span class="badge bg-success">En Funciones</span>
                    @else
                        <span class="badge bg-warning text-dark">No Asignado</span>
                    @endif
                </div>
                <div class="card-body">
                    @if($registradorActual)
                        <h5 class="card-title text-primary mb-1">
                            {{ $registradorActual->titulo_profesional }} {{ $registradorActual->nombre_completo }}
                        </h5>
                        <p class="card-text text-muted mb-2">
                            <small>
                                <strong>Fecha de Inicio:</strong> {{ $registradorActual->fecha_inicio ? $registradorActual->fecha_inicio->format('d/m/Y') : '-' }} <br>
                                <strong>Acción / Nro. Resolución:</strong> {{ $registradorActual->accion_designacion ?? 'Sin registrar' }}
                            </small>
                        </p>
                    @else
                        <p class="text-muted">No hay un registrador activo registrado actualmente.</p>
                    @endif

                    <button class="btn btn-outline-primary btn-sm mt-2" data-bs-toggle="collapse" data-bs-target="#formNuevoRegistrador">
                        <i class="bi bi-person-plus-fill me-1"></i> Designar Nuevo Registrador
                    </button>
                </div>
            </div>

            {{-- Formulario Desplegable para Nuevo Registrador --}}
            <div class="collapse mb-4" id="formNuevoRegistrador">
                <div class="card shadow-sm border-0 bg-light">
                    <div class="card-body">
                        <h6 class="card-title font-weight-bold text-dark mb-3">Designar Nuevo Registrador</h6>
                        <form action="{{ route('configuracion.cambiarRegistrador') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-4 mb-2">
                                    <label for="titulo_profesional" class="form-label form-label-sm">Título:</label>
                                    <input type="text" class="form-control form-control-sm" id="titulo_profesional" name="titulo_profesional" placeholder="Ej. Abg. / Dr.">
                                </div>
                                <div class="col-md-8 mb-2">
                                    <label for="nombre_completo" class="form-label form-label-sm">Nombre Completo:*</label>
                                    <input type="text" class="form-control form-control-sm" id="nombre_completo" name="nombre_completo" required placeholder="Nombres y Apellidos">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label for="fecha_inicio" class="form-label form-label-sm">Fecha de Posesión/Inicio:*</label>
                                    <input type="date" class="form-control form-control-sm" id="fecha_inicio" name="fecha_inicio" required value="{{ date('Y-m-d') }}">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="accion_designacion" class="form-label form-label-sm">Nro. Acción de Personal / Res:</label>
                                    <input type="text" class="form-control form-control-sm" id="accion_designacion" name="accion_designacion" placeholder="Ej. Res-2026-001">
                                </div>
                            </div>
                            <div class="text-end mt-3">
                                <button type="submit" class="btn btn-sm btn-success">
                                    <i class="bi bi-check-lg me-1"></i> Guardar y Activar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>

    </div>

    {{-- FILA INFERIOR: Historial de Registradores --}}
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-secondary text-white font-weight-bold">
                    <i class="bi bi-clock-history me-2"></i> Historial de Registradores Anteriores
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Registrador</th>
                                    <th>Fecha Inicio</th>
                                    <th>Fecha Fin</th>
                                    <th>Resolución / Nro. Acción</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($historialRegistradores as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <strong>{{ $item->titulo_profesional }} {{ $item->nombre_completo }}</strong>
                                        </td>
                                        <td>{{ $item->fecha_inicio ? $item->fecha_inicio->format('d/m/Y') : '-' }}</td>
                                        <td>{{ $item->fecha_fin ? $item->fecha_fin->format('d/m/Y') : 'Actualidad' }}</td>
                                        <td>{{ $item->accion_designacion ?? 'N/A' }}</td>
                                        <td>
                                            @if($item->es_actual)
                                                <span class="badge bg-success">Activo</span>
                                            @else
                                                <span class="badge bg-secondary">Finalizado</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-3">No hay historial registrado.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection