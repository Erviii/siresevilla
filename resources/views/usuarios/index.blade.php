@extends('layouts.base')

@section('content')
<div class="card border-0 shadow-sm rounded-4 p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark m-0">
                <i class="bi bi-people-fill text-primary me-2"></i>Gestión de Usuarios y Roles
            </h4>
            <p class="text-muted small m-0">Control de cuentas de acceso e identidades del SIRE</p>
        </div>
        <!-- Botón Crear Usuario -->
        <button type="button" class="btn btn-primary fw-semibold rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalCrearUsuario">
            <i class="bi bi-person-plus-fill me-1"></i> Nuevo Usuario
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
                    <th>Usuario (Login)</th>
                    <th>Nombre Completo</th>
                    <th>Cargo Institucional</th>
                    <th>Rol de Acceso</th>
                    <th>Fecha Creacion</th>
                    <th>Estado</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($usuarios as $user)
                    @php
                        $userLogin = trim($user->usualogin);
                        $userStatus = trim($user->usuastatus);
                        $userRole = $user->roles->first()->name ?? '';
                    @endphp
                    <tr>
                        <td class="fw-bold font-monospace text-primary">{{ $userLogin }}</td>
                        <td>{{ $user->usuanombre }}</td>
                        <td><small class="text-muted">{{ $user->usuatitulo ?? 'N/A' }}</small></td>
                        <td>
                            @forelse($user->getRoleNames() as $rol)
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3">
                                    <i class="bi bi-shield-lock me-1"></i>{{ $rol }}
                                </span>
                            @empty
                                <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3">Sin Rol</span>
                            @endforelse
                        </td>
                        <td>
                            <small class="text-muted">{{ $user->usuafecing ?? 'N/A' }}
                        </td>
                        <td>
                            @if($user->usuastatus == 'AC')
                                <span class="badge bg-success-subtle text-success px-2.5 py-1 rounded-pill">Activo</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger px-2.5 py-1 rounded-pill">Inactivo</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <!-- Botón Editar que dispara el Modal Editar y pasa los datos por Data Attributes -->
                            <button type="button" 
                                    class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-sm btn-editar-usuario"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalEditarUsuario"
                                    data-login="{{ $userLogin }}"
                                    data-nombre="{{ $user->usuanombr }}"
                                    data-cargo="{{ $user->usuacargo }}"
                                    data-status="{{ $userStatus }}"
                                    data-role="{{ $userRole }}"
                                    data-action="{{ route('usuarios.update', $userLogin) }}">
                                <i class="bi bi-pencil-square me-1"></i> Editar
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No hay usuarios registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL CREAR USUARIO -->
<div class="modal fade" id="modalCrearUsuario" tabindex="-1" aria-labelledby="modalCrearUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-primary text-white py-3 px-4 rounded-top-4">
                <h5 class="modal-header-title modal-title fw-bold fs-6 d-flex align-items-center" id="modalCrearUsuarioLabel">
                    <i class="bi bi-person-plus-fill me-2"></i> Registrar Nuevo Usuario Registral
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ route('usuarios.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">Usuario (Login):</label>
                            <input type="text" name="usualogin" class="form-control" placeholder="Ej: mmendoza" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">Nombre Completo:</label>
                            <input type="text" name="usuanombr" class="form-control" placeholder="Ej: Mario Mendoza" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">Contraseña de Acceso:</label>
                            <input type="password" name="usuapasswr" class="form-control" placeholder="••••••••" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">Rol de Sistema (Permisos):</label>
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
                </div>
                
                <div class="modal-footer bg-light px-4 py-3 rounded-bottom-4 border-top-0">
                    <button type="button" class="btn btn-light border rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm">
                        <i class="bi bi-save me-1"></i> Guardar Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL EDITAR USUARIO -->
<div class="modal fade" id="modalEditarUsuario" tabindex="-1" aria-labelledby="modalEditarUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-primary text-white py-3 px-4 rounded-top-4">
                <h5 class="modal-header-title modal-title fw-bold fs-6 d-flex align-items-center" id="modalEditarUsuarioLabel">
                    <i class="bi bi-pencil-square me-2"></i> Editar Usuario Registral: <span id="edit_title_login" class="ms-2 font-monospace text-warning"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="formEditarUsuario" method="POST">
                @csrf
                @method('PUT')
                
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">Usuario (Login):</label>
                            <input type="text" id="edit_usualogin" class="form-control bg-light" readonly disabled>
                            <small class="text-muted" style="font-size: 0.75rem;">El login de usuario no se puede modificar.</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">Nombre Completo:</label>
                            <input type="text" name="usuanombr" id="edit_usuanombr" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">Cambiar Contraseña (Opcional):</label>
                            <input type="password" name="usuapasswr" class="form-control" placeholder="Dejar en blanco para mantener la actual">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">Rol de Sistema (Permisos):</label>
                            <select name="role" id="edit_role" class="form-select" required>
                                <option value="">Seleccione un Rol...</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-8">
                            <label class="form-label fw-semibold text-secondary">Cargo Institucional:</label>
                            <input type="text" name="usuacargo" id="edit_usuacargo" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-secondary">Estado de Cuenta:</label>
                            <select name="usuastatus" id="edit_usuastatus" class="form-select">
                                <option value="AC">Activo</option>
                                <option value="IN">Inactivo</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer bg-light px-4 py-3 rounded-bottom-4 border-top-0">
                    <button type="button" class="btn btn-light border rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm">
                        <i class="bi bi-save me-1"></i> Actualizar Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- SCRIPT PARA CARGAR DATOS EN EL MODAL DE EDICIÓN -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const editButtons = document.querySelectorAll('.btn-editar-usuario');
    
    editButtons.forEach(button => {
        button.addEventListener('click', function () {
            const login = this.getAttribute('data-login');
            const nombre = this.getAttribute('data-nombre');
            const cargo = this.getAttribute('data-cargo');
            const status = this.getAttribute('data-status');
            const role = this.getAttribute('data-role');
            const action = this.getAttribute('data-action');

            // Cargar valores en los inputs del modal
            document.getElementById('edit_title_login').textContent = login;
            document.getElementById('edit_usualogin').value = login;
            document.getElementById('edit_usuanombr').value = nombre;
            document.getElementById('edit_usuacargo').value = cargo;
            document.getElementById('edit_usuastatus').value = status;
            document.getElementById('edit_role').value = role;

            // Actualizar el action del formulario con la URL adecuada
            document.getElementById('formEditarUsuario').setAttribute('action', action);
        });
    });
});
</script>
@endsection