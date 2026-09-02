@extends('layouts.base')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h5 class="fw-bold text-dark m-0">
                        <i class="bi bi-shield-lock-fill text-primary me-2"></i>Editar Matriz de Permisos del Rol: <span class="text-primary">{{ $role->name }}</span>
                    </h5>
                    <small class="text-muted">Marque o desmarque los accesos permitidos para este nivel de perfil</small>
                </div>
            </div>

            <form action="{{ route('roles.update', $role->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Nombre del Rol -->
                <div class="mb-4">
                    <label class="form-label fw-semibold text-secondary">Nombre del Rol:</label>
                    <input type="text" name="name" class="form-control form-control-lg w-50" value="{{ old('name', $role->name) }}" required>
                </div>

                <hr class="my-4 opacity-25">

                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-ui-checks me-2 text-primary"></i>Permisos del Sistema por Módulo:</h6>

                <!-- Matriz de Permisos con Checkboxes -->
                <div class="row g-3">
                    @forelse($permissions as $perm)
                        <div class="col-md-4">
                            <div class="card border rounded-3 p-3 bg-light hover-shadow transition-all">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           name="permissions[]" 
                                           value="{{ $perm->name }}" 
                                           id="perm_{{ $perm->id }}"
                                           {{ in_array($perm->name, $rolePermissions) ? 'checked' : '' }}>
                                    
                                    <label class="form-check-label fw-semibold text-dark cursor-pointer ms-2" for="perm_{{ $perm->id }}">
                                        {{ ucfirst(str_replace('-', ' ', $perm->name)) }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-muted">No existen permisos registrados en el sistema.</div>
                    @endforelse
                </div>

                <div class="d-flex justify-content-end gap-2 mt-5">
                    <a href="{{ route('roles.index') }}" class="btn btn-light border rounded-pill px-4">Cancelar</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm">
                        <i class="bi bi-save me-1"></i> Guardar Permisos
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection