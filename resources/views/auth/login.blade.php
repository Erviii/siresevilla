<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso - Sistema de Información Registral (SIRE)</title>
    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --sire-primary: #1e3a8a;
            --sire-secondary: #0f172a;
            --sire-accent: #0284c7;
        }

        body {
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            min-height: 100vh;
        }

        .login-card {
            border-radius: 1.25rem;
            overflow: hidden;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.6);
        }

        .login-brand-panel {
            background: linear-gradient(160deg, var(--sire-secondary) 0%, var(--sire-primary) 100%);
            color: #ffffff;
            position: relative;
        }

        .login-brand-panel::before {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: radial-gradient(circle at 20% 20%, rgba(2, 132, 199, 0.2) 0%, transparent 50%);
            pointer-events: none;
        }

        .form-control:focus {
            border-color: var(--sire-accent);
            box-shadow: 0 0 0 0.25rem rgba(2, 132, 199, 0.15);
        }

        .btn-sire {
            background: linear-gradient(135deg, var(--sire-primary) 0%, #2563eb 100%);
            border: none;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-sire:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(37, 99, 235, 0.3);
            color: white;
        }

        .input-group-text {
            background-color: #f8fafc;
            border-right: none;
        }

        .input-with-icon {
            border-left: none;
        }

        .toggle-password {
            cursor: pointer;
            background-color: #f8fafc;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center py-4">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-9">
            <div class="card login-card border-0">
                <div class="row g-0">
                    
                    <!-- PANEL IZQUIERDO: Branding Institucional -->
                    <div class="col-md-6 login-brand-panel d-flex flex-column justify-content-between p-4 p-lg-5 text-center text-md-start">
                        <div>
                            <div class="d-flex align-items-center justify-content-center justify-content-md-start mb-3">
                                <span class="badge bg-white bg-opacity-10 text-white px-3 py-2 rounded-pill font-monospace small">
                                    <i class="bi bi-shield-check me-1 text-info"></i> SIRE v1.6
                                </span>
                            </div>
                            <h3 class="fw-bold text-white mb-2">Registro de la Propiedad y Mercantil</h3>
                            <p class="text-white-50 small">Gestión Registral, Seguridad Jurídica y Fe Pública Digital</p>
                        </div>

                        <!-- Logo Institucional -->
                        <div class="my-4 my-md-0 text-center py-3">
                            <div class="bg-white p-3 rounded-4 d-inline-block shadow-sm">
                                <img src="{{ asset('images/logosevilla.png') }}" 
                                     alt="Logo GAD Sevilla Don Bosco" 
                                     class="img-fluid" 
                                     style="max-height: 120px; object-fit: contain;">
                            </div>
                            <div class="mt-3 text-white-50 small fw-semibold">
                                GAD Municipal del Cantón Sevilla Don Bosco
                            </div>
                        </div>

                        <div class="text-white-50 small d-none d-md-block">
                            <i class="bi bi-lock me-1"></i> Acceso restringido únicamente a personal autorizado.
                        </div>
                    </div>

                    <!-- PANEL DERECHO: Formulario de Login -->
                    <div class="col-md-6 bg-white p-4 p-lg-5 d-flex flex-column justify-content-center">
                        
                        <div class="mb-4">
                            <h4 class="fw-bold text-dark mb-1">Iniciar Sesión</h4>
                            <p class="text-muted small">Ingrese sus credenciales corporativas para continuar</p>
                        </div>

                        <!-- Alertas de Error -->
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm py-2 px-3 mb-4" role="alert">
                                <div class="d-flex align-items-center small">
                                    <i class="bi bi-exclamation-triangle-fill me-2 fs-6"></i>
                                    <div>{{ $errors->first() }}</div>
                                </div>
                                <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('login.post') }}" method="POST">
                            @csrf
                            
                            <!-- Campo Usuario -->
                            <div class="mb-3">
                                <label for="usuario" class="form-label fw-semibold text-secondary small">Usuario Registral</label>
                                <div class="input-group">
                                    <span class="input-group-text text-muted">
                                        <i class="bi bi-person"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control form-control-lg input-with-icon fs-6" 
                                           id="usuario" 
                                           name="usuario" 
                                           value="{{ old('usuario') }}" 
                                           placeholder="Ej: admin_registral" 
                                           required 
                                           autofocus>
                                </div>
                            </div>
                            
                            <!-- Campo Contraseña -->
                            <div class="mb-4">
                                <label for="password" class="form-label fw-semibold text-secondary small">Contraseña</label>
                                <div class="input-group">
                                    <span class="input-group-text text-muted">
                                        <i class="bi bi-key"></i>
                                    </span>
                                    <input type="password" 
                                           class="form-control form-control-lg input-with-icon border-end-0 fs-6" 
                                           id="password" 
                                           name="password" 
                                           placeholder="••••••••••••" 
                                           required>
                                    <span class="input-group-text toggle-password text-muted" onclick="togglePassword()">
                                        <i class="bi bi-eye-slash" id="toggleIcon"></i>
                                    </span>
                                </div>
                            </div>

                            <!-- Botón Ingresar -->
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-sire btn-lg fw-semibold py-2.5 fs-6 shadow-sm">
                                    <i class="bi bi-box-arrow-in-right me-2"></i> Ingresar al Sistema
                                </button>
                            </div>
                        </form>

                        <div class="text-center mt-3 pt-3 border-top">
                            <span class="text-muted extra-small" style="font-size: 0.75rem;">
                                &copy; {{ date('Y') }} SIRE - Todos los derechos reservados
                            </span>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Función para mostrar / ocultar contraseña
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('bi-eye-slash');
            toggleIcon.classList.add('bi-eye');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('bi-eye');
            toggleIcon.classList.add('bi-eye-slash');
        }
    }
</script>

</body>
</html>