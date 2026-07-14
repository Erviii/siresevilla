<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIRE - Sistema Registral')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
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
            <span class="fw-bold text-tracking">SIRE - Sistema Registral</span>
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
                                <span class="dropdown-item-text text-muted small">
                                    <i class="bi bi-shield-lock me-1"></i> Sesión Activa
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
    
    <!-- Barra de Direcciones / Breadcrumbs -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-white p-3 rounded shadow-sm">
            <li class="breadcrumb-item">
                <a href="{{ route('sire.dashboard') }}" class="text-decoration-none text-primary fw-semibold">
                    <i class="bi bi-house-door-fill me-1"></i>Inicio
                </a>
            </li>
            @yield('breadcrumbs')
        </ol>
    </nav>

    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts') </body>
</html>