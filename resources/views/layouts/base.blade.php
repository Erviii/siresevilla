<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIRE - Sistema Registral')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Cargar jQuery PRIMERO -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Cargar Bootstrap JS después de jQuery -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- TUS SCRIPTS PERSONALIZADOS SIEMPRE AL FINAL -->
@stack('scripts')
    <style>
        body { background-color: #f4f6f9; padding-bottom: 40px; }
        .navbar-custom { background-color: #1a5c96; }
        .card { border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border-radius: 10px; }
        .card-stat { border: none; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); transition: transform 0.2s; }
        .card-stat:hover { transform: translateY(-5px); }
        .btn-action { transition: all 0.2s; border: 1px solid #dee2e6; }
        .btn-action:hover { background-color: #f8f9fa; transform: translateX(3px); }
        .section-title { border-bottom: 2px solid #1a5c96; padding-bottom: 5px; color: #1a5c96; font-weight: bold; }
    </style>
    @stack('styles') </head>
<body>

<!-- Navbar Principal Estático -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom mb-4 shadow-sm">
    <div class="container-fluid px-4">
        <!-- Logo / Inicio -->
        <a class="navbar-brand d-flex align-items-center" href="{{ route('sire.dashboard') }}">
            <i class="bi bi-layers-half me-2 fs-3"></i>
            <span class="fw-bold text-tracking">SIRE - Registro de la Propiedad y Mercantil Sevilla Don Bosco</span>
        </a>

        <!-- Botón de colapso para móviles -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDarkDropdown" aria-controls="navbarNavDarkDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Sección de Usuario y Cerrar Sesión -->
        <div class="collapse navbar-collapse justify-content-end" id="navbarNavDarkDropdown">
            <ul class="navbar-nav">
                @auth
                    <li class="nav-item dropdown">
    <button class="btn btn-link nav-link dropdown-toggle text-white d-flex align-items-center border-0" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-person-circle me-2 fs-5"></i>
        <span>{{ Auth::user()->usuanombre ?? Auth::user()->uanombre ?? 'Operador Registral' }}</span>
    </button>
    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="navbarDarkDropdownMenuLink">
        <li>
            <span class="dropdown-item-text d-flex flex-column pb-1">
                <span class="text-muted small">
                    <i class="bi bi-shield-lock me-1"></i> 
                    <!-- Muestra el rol aquí -->
                    {{ Auth::user()->getRoleNames()->first() ?? 'Sin Rol Asignado' }}
                </span>
            </span>
        </li>
        <li><hr class="dropdown-divider"></li>
        <li>
            <!-- Formulario de salida seguro -->
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="dropdown-item text-danger d-flex align-items-center">
                    <i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión
                </button>
            </form>
        </li>
    </ul>
</li>
                @else
                    <li class="nav-item">
                        <a href="{{ route('login') }}" class="nav-link text-white">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Iniciar Sesión
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<!-- Contenedor del Contenido Dinámico -->
<div class="container-fluid px-4">
    
   <nav aria-label="breadcrumb" class="mb-4">
    <div class="bg-white p-3 rounded shadow-sm d-flex justify-content-between align-items-center flex-wrap gap-2">
        <!-- Breadcrumbs a la izquierda -->
        <ol class="breadcrumb m-0 p-0">
            <li class="breadcrumb-item">
                <a href="{{ route('sire.dashboard') }}" class="text-decoration-none text-primary fw-semibold">
                    <i class="bi bi-house-door-fill me-1"></i>Inicio
                </a>
            </li>
            @yield('breadcrumbs')
        </ol>

        <!-- Reloj con Fecha y Hora en tiempo real a la derecha -->
        <div class="text-muted small fw-semibold d-flex align-items-center bg-light px-3 py-1 rounded-pill border">
            <i class="bi bi-calendar3 text-primary me-2"></i>
            <span id="reloj-fecha" class="me-2 text-capitalize"></span>
            <span class="text-secondary me-2">|</span>
            <i class="bi bi-clock-history text-primary me-1"></i>
            <span id="reloj-hora" class="font-monospace fw-bold text-dark"></span>
        </div>
    </div>
</nav>

    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Script del Reloj en Tiempo Real -->
    <script>
        function actualizarReloj() {
            const ahora = new Date();

            // Formato de Fecha en Español (ej: domingo, 16 de agosto de 2026)
            const opcionesFecha = { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            };
            let fechaTexto = ahora.toLocaleDateString('es-ES', opcionesFecha);
            
            // Capitalizar primera letra del día
            fechaTexto = fechaTexto.charAt(0).toUpperCase() + fechaTexto.slice(1);

            // Formato de Hora (ej: 01:21:32 p. m.)
            const horaTexto = ahora.toLocaleTimeString('es-ES', { 
                hour: '2-digit', 
                minute: '2-digit', 
                second: '2-digit', 
                hour12: true 
            });

            const elemFecha = document.getElementById('reloj-fecha');
            const elemHora = document.getElementById('reloj-hora');

            if (elemFecha && elemHora) {
                elemFecha.textContent = fechaTexto;
                elemHora.textContent = horaTexto;
            }
        }

        // Ejecutar al cargar la página y actualizar cada 1 segundo
        document.addEventListener('DOMContentLoaded', function() {
            actualizarReloj();
            setInterval(actualizarReloj, 1000);
        });
    </script>

@stack('scripts') </body>
</html>