@extends('layouts.base')

@section('title', 'Registrar Nuevo Movimiento - SIRE')

@push('styles')
<style>
    /* Estilos específicos para la vista de registro */
    .section-title { 
        border-bottom: 2px solid #1a5c96; 
        padding-bottom: 5px; 
        color: #1a5c96; 
        font-weight: bold; 
    }
    .fila-interviniente {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 10px;
        border: 1px dashed #dee2e6;
        transition: all 0.2s ease;
    }
    .fila-interviniente:hover {
        background-color: #f1f3f5;
        border-color: #ced4da;
    }
</style>
@endpush

@section('content')
<div class="row justify-content-center mb-5">
    <div class="col-xl-8">
        
        <div class="card shadow-sm">
            <!-- Cabecera de la Tarjeta con estilo unificado -->
            <div class="card-header bg-primary text-white p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0 fw-bold"><i class="bi bi-journal-plus me-2"></i>SIRE - Ingreso de Movimiento Registral</h4>
                        <small class="text-white-50">Asociado a la Ficha Nº: <strong>{{ $ficha->fichnumfic }}</strong></small>
                    </div>
                    <span class="badge bg-light text-primary fs-6 py-2 px-3">Ficha Nº {{ $ficha->fichnumfic }}</span>
                </div>
            </div>
            
            <div class="card-body p-4">
                
                <!-- Alertas de Éxito / Error -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Formulario Registral -->
                <form action="{{ route('sire.storeMovimiento') }}" method="POST">
                    @csrf
                    <!-- Inputs Ocultos de Control -->
                    <input type="hidden" name="fichnumfic" value="{{ $ficha->fichnumfic }}">
                    <input type="hidden" name="tip_fic" value="{{ $ficha->fichtipfic }}">

                    <!-- 1. Datos Generales de Inscripción -->
                    <h5 class="section-title mb-3 fs-5"><i class="bi bi-info-circle me-2"></i>1. Datos Generales de Inscripción</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-secondary">Libro:</label>
                            <select name="cod_lib" class="form-select" required>
                                <option value="">Seleccione...</option>
                                @foreach($libros as $libro)
                                    <option value="{{ $libro->librcodlib }}">{{ $libro->librnombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-secondary">Nº Repertorio:</label>
                            <input type="number" step="1" name="num_rep" class="form-control" required placeholder="Ej: 12450">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-secondary">Nº Inscripción:</label>
                            <input type="number" step="1" name="num_ins" class="form-control" required placeholder="Ej: 852">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-secondary">Fecha de Inscripción:</label>
                            <input type="date" name="fec_ins" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <!-- 2. Información del Acto Registral -->
                    <h5 class="section-title mb-3 fs-5"><i class="bi bi-file-earmark-text me-2"></i>2. Información del Acto Registral</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-secondary">Tipo de Acto:</label>
                            <select name="cod_acto" class="form-select" required>
                                <option value="">Seleccione...</option>
                                @foreach($actos as $acto)
                                    <option value="{{ $acto->actocodact }}">{{ $acto->actonombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-secondary">Cantón:</label>
                            <select name="cod_can" class="form-select" required>
                                <option value="">Seleccione...</option>
                                @foreach($cantones as $canton)
                                    <option value="{{ $canton->cantcodcan }}">{{ $canton->cantnombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-secondary">Juzgado / Notaría:</label>
                            <select name="cod_jon" class="form-select" required>
                                <option value="">Seleccione...</option>
                                @foreach($juzgados as $juzgado)
                                    <option value="{{ $juzgado->junocodjon }}">{{ $juzgado->junonombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- 3. Observaciones / Nota Marginal -->
                    <h5 class="section-title mb-3 fs-5"><i class="bi bi-journal-text me-2"></i>3. Observaciones / Nota Marginal</h5>
                    <div class="row mb-4">
                        <div class="col-12">
                            <textarea name="observacion" class="form-control" rows="4" placeholder="Escriba aquí los detalles, linderos adicionales u observaciones pertinentes al movimiento..." required></textarea>
                        </div>
                    </div>

                    <!-- 4. Intervinientes Registrados -->
                    <h5 class="section-title mb-3 fs-5 d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-people me-2"></i>4. Intervinientes Registrados</span>
                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="agregarFila()">
                            <i class="bi bi-plus-circle me-1"></i> Agregar Interviniente
                        </button>
                    </h5>
                    
                    <div id="contenedor-intervinientes" class="mb-4">
                        <div class="row g-2 mb-2 align-items-end fila-interviniente">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold text-secondary small d-none d-md-block">Rol</label>
                                <select name="roles[]" class="form-select" required>
                                    <option value="V">Vendedor / Otorgante (V)</option>
                                    <option value="C">Comprador / Beneficiario (C)</option>
                                    <option value="A">Acreedor (A)</option>
                                    <option value="D">Deudor (D)</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold text-secondary small d-none d-md-block">Identificación</label>
                                <input type="text" name="cedulas[]" class="form-control" placeholder="Cédula o RUC" maxlength="10" required>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label fw-semibold text-secondary small d-none d-md-block">Nombres Completos</label>
                                <input type="text" name="nombres[]" class="form-control" placeholder="Nombres y Apellidos" required>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-outline-danger w-100" onclick="eliminarFila(this)" title="Eliminar fila">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4 text-muted opacity-25">

                    <!-- Botones de Acción inferior -->
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('sire.dashboard') }}" class="btn btn-light border px-4">
                            <i class="bi bi-x-circle me-2"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary px-4 shadow-sm">
                            <i class="bi bi-save me-2"></i>Guardar Movimiento
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    function agregarFila() {
        const contenedor = document.getElementById('contenedor-intervinientes');
        const nuevaFila = document.createElement('div');
        nuevaFila.className = 'row g-2 mb-2 align-items-end fila-interviniente';
        nuevaFila.innerHTML = `
            <div class="col-md-3">
                <select name="roles[]" class="form-select" required>
                    <option value="V">Vendedor / Otorgante (V)</option>
                    <option value="C">Comprador / Beneficiario (C)</option>
                    <option value="A">Acreedor (A)</option>
                    <option value="D">Deudor (D)</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="text" name="cedulas[]" class="form-control" placeholder="Cédula o RUC" maxlength="10" required>
            </div>
            <div class="col-md-5">
                <input type="text" name="nombres[]" class="form-control" placeholder="Nombres y Apellidos" required>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-outline-danger w-100" onclick="eliminarFila(this)" title="Eliminar fila">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        `;
        contenedor.appendChild(nuevaFila);
    }

    function eliminarFila(btn) {
        const filas = document.querySelectorAll('.fila-interviniente');
        if (filas.length > 1) {
            btn.closest('.fila-interviniente').remove();
        } else {
            alert("Debe registrar al menos un interviniente para el movimiento.");
        }
    }
</script>
@endpush