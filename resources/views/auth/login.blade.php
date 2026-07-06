<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso - Sistema de Información Registral</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="min-height: 100vh;">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-lg border-0 rounded-lg">
                
                <div class="card-header bg-dark text-white text-center py-3">
                    <h5 class="mb-0">🏛️ Sistema de Información Registral</h5>
                </div>
                
                <div class="card-body p-4">
                    
                    <div class="text-center mb-4">
                        <img src="{{ asset('images/logosevilla.png') }}" 
                             alt="Logo GAD Sevilla" 
                             class="img-fluid" 
                             style="max-height: 120px; object-fit: contain;">
                    </div>
                    @if($errors->any())
                        <div class="alert alert-danger py-2">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form action="{{ route('login.post') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="usuario" class="form-label fw-bold">Usuario:</label>
                            <input type="text" 
                                   class="form-control form-control-lg" 
                                   id="usuario" 
                                   name="usuario" 
                                   value="{{ old('usuario') }}" 
                                   placeholder="Ej: Administrador" 
                                   required 
                                   autofocus>
                        </div>
                        
                        <div class="mb-4">
                            <label for="password" class="form-label fw-bold">Contraseña:</label>
                            <input type="password" 
                                   class="form-control form-control-lg" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Ingresa tu clave" 
                                   required>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold">
                                🔑 Ingresar al Sistema
                            </button>
                        </div>
                    </form>
                    
                </div>
                <div class="card-footer text-center py-3 bg-light border-0">
                    <small class="text-muted">Módulo de Seguridad - SIRE</small>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>