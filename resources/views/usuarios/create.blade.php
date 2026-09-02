@extends('layouts.base')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <h5 class="fw-bold text-dark mb-3">
                <i class="bi bi-person-plus text-primary me-2"></i>Crear Nuevo Usuario Registral
            </h5>
            
            <form action="{{ route('usuarios.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary">Usuario (Login de Sistema):</label>
                        <input type="text" name="usualogin" class="form-control" placeholder="Ej: mmendoza" required autofocus>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary">Nombre Completo:</label>
                        <input type="text" name="usuanombr" class="form-control" placeholder="Ej: Mario Mendoza" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary">Contraseña:</label>
                        <input type="password" name="usuapasswr" class="form-control" placeholder="••••••••" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary">Rol de Sistema (Nivel de Permisos):</label>
                        <select name="role" class="form-select" required>
                            <option value="">Seleccione un Rol...</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold text-secondary">Cargo Institucional:</label>
                        <input type="text" name="usuacargo" class="form-control" placeholder="Ej: REGISTRADOR DE LA PROPIEDAD Y MERCANTIL (E)">
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('usuarios.index') }}" class="btn btn-light border rounded-pill px-4">Cancelar</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold">Guardar Usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection