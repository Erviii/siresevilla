@extends('layouts.base')

@section('content')
<div class="card border-0 shadow-sm rounded-4 p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark m-0">
                <i class="bi bi-shield-lock-fill text-primary me-2"></i>Matriz de Roles y Permisos
            </h4>
            <p class="text-muted small m-0">Administración de perfiles de acceso y permisos asignados en el SIRE</p>
        </div>
        <!-- Botón para abrir el Modal -->
        <button type="button" class="btn btn-primary fw-semibold rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalCrearRol">
            <i class="bi bi-plus-circle-fill me-1"></i> Nuevo Rol
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ $errors->first() }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width: 20%;">Rol / Perfil</th>
                    <th style="width: 65%;">Permisos Habilitados</th>
                    <th style="width: 15%;" class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($roles as $role)
                    <tr>
                        <td>
                            <div class="fw-bold text-dark d-flex align-items-center">
                                <i class="bi bi-shield-check text-primary me-2 fs-5"></i>
                                {{ $role->name }}
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-wrap gap-1">
                                @forelse($role->permissions as $perm)
                                    <span class="badge bg-light text-dark border rounded-pill px-2.5 py-1.5 small fw-normal">
                                        <i class="bi bi-check2 text-success me-1"></i>{{ ucfirst(str_replace('-', ' ', $perm->name)) }}
                                    </span>
                                @empty
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3">
                                        Sin permisos asignados
                                    </span>
                                @endforelse
                            </div>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-sm" title="Editar Permisos">
                                <i class="bi bi-pencil-square me-1"></i> Permisos
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted py-4">No hay roles registrados en el sistema.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL CREAR ROL -->
<div class="modal fade" id="modalCrearRol" tabindex="-1" aria-labelledby="modalCrearRolLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-primary text-white py-3 px-4 rounded-top-4">
                <h5 class="modal-header-title modal-title fw-bold fs-6 d-flex align-items-center" id="modalCrearRolLabel">
                    <i class="bi bi-shield-plus me-2"></i> Crear Nuevo Rol de Sistema
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ route('roles.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    
                    <!-- Campo Nombre del Rol -->
                    <div class="mb-4">
                        <label for="roleName" class="form-label fw-semibold text-secondary">Nombre del Rol:</label>
                        <input type="text" 
                               name="name" 
                               id="roleName" 
                               class="form-control form-control-lg" 
                               placeholder="Ej: Certificador, Cajero, Abogado Revisor" 
                               required 
                               autofocus>
                    </div>

                    <hr class="my-4 opacity-25">

                    <h6 class="fw-bold text-dark mb-3">
                        <i class="bi bi-ui-checks me-2 text-primary"></i>Asignar Permisos Iniciales (Opcional):
                    </h6>

                    <!-- Grid de Permisos dentro del Modal -->
                    <div class="row g-2 style-scrollbar" style="max-height: 280px; overflow-y: auto;">
                        @forelse($permissions as $perm)
                            <div class="col-md-6">
                                <div class="card border rounded-3 p-2 bg-light">
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               name="permissions[]" 
                                               value="{{ $perm->name }}" 
                                               id="modal_perm_{{ $perm->id }}">
                                        
                                        <label class="form-check-label fw-semibold text-dark cursor-pointer ms-2 small" for="modal_perm_{{ $perm->id }}">
                                            {{ ucfirst(str_replace('-', ' ', $perm->name)) }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-muted small">No hay permisos disponibles registrados.</div>
                        @endforelse
                    </div>

                </div>
                
                <div class="modal-footer bg-light px-4 py-3 rounded-bottom-4 border-top-0">
                    <button type="button" class="btn btn-light border rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm">
                        <i class="bi bi-save me-1"></i> Guardar Rol
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection