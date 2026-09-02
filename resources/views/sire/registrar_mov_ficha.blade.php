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
                        <small class="text-white-50">Asociado a la Ficha Nº: SBD-<strong>{{ $ficha->fichnumfic }}</strong></small>
                    </div>
                    <span class="badge bg-light text-primary fs-6 py-2 px-3">Ficha Nº SDB-{{ $ficha->fichnumfic }}</span>
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

                <!-- Formulario Registral (Añadido id="formMovimiento") -->
                <form id="formMovimiento" action="{{ route('sire.storeMovimiento') }}" method="POST">
                    @csrf
                    <!-- Inputs Ocultos de Control -->
                    <input type="hidden" name="fichnumfic" value="{{ $ficha->fichnumfic }}">
                    <input type="hidden" name="tip_fic" value="{{ $ficha->fichtipfic }}">

                    <!-- 1. Datos Generales de Inscripción -->
                    <h5 class="section-title mb-3 fs-5"><i class="bi bi-info-circle me-2"></i>1. Datos Generales de Inscripción</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-2">
                            <label class="form-label fw-semibold text-secondary">Libro:</label>
                            <select name="cod_lib" class="form-select" required>
                                <option value="">Seleccione...</option>
                                @foreach($libros as $libro)
                                    <option value="{{ $libro->librcodlib }}">{{ $libro->librnombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- NUEVO CAMPO: TOMO -->
                        <div class="col-md-2">
                            <label class="form-label fw-semibold text-secondary">Tomo:</label>
                            <input type="number" step="1" name="num_tom" class="form-control" required placeholder="Ej: 1">
                        </div>
                        <!-- FIN NUEVO CAMPO -->
                        <div class="col-md-2">
                            <label class="form-label fw-semibold text-secondary">Nº Rep:</label>
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
                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" id="btnAgregarInterviniente">
                            <i class="bi bi-plus-circle me-1"></i> Agregar Interviniente
                        </button>
                    </h5>
                    
                    <div id="contenedor-intervinientes" class="mb-4">
                        <div class="row g-2 mb-2 align-items-end fila-interviniente">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold text-secondary small d-none d-md-block">Rol</label>
                                <select name="roles[]" class="form-select select-rol" required>
                                    <option value="">Seleccione...</option>
                                    @foreach($rolcliente as $rol)
                                        <option value="{{ $rol->papecodtip }}">{{ $rol->papenombre }}</option>
                                    @endforeach
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

                        <button type="button" id="btnPreview" class="btn btn-info me-2 text-white">
                            <i class="bi bi-eye me-1"></i> Vista Previa
                        </button>

                        <button type="submit" class="btn btn-primary px-4 shadow-sm">
                            <i class="bi bi-save me-2"></i>Guardar Movimiento
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<!-- Modal de Previsualización -->
<div id="modalPreview" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold"><i class="bi bi-eye me-2"></i>Vista Previa del Movimiento</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Cabecera de Inscripción (AHORA INCLUYE TOMO) -->
                <div class="preview-header bg-light p-3 mb-3 rounded border">
                    <div class="row text-center">
                        <div class="col-md-3"><strong>TOMO:</strong> <span id="prev_tom"></span></div>
                        <div class="col-md-3"><strong>REP N°:</strong> <span id="prev_rep"></span></div>
                        <div class="col-md-3"><strong>INS N°:</strong> <span id="prev_ins"></span></div>
                        <div class="col-md-3"><strong>FECHA:</strong> <span id="prev_fec"></span></div>
                    </div>
                </div>

                <!-- Tipo de Acto -->
                <div class="mb-3">
                    <h6 class="fw-bold text-primary mb-1"><i class="bi bi-file-earmark-text me-1"></i>Tipo de Acto Registral:</h6>
                    <p class="ps-3 mb-0 border-start border-3 border-primary bg-light p-2 rounded" id="prev_acto"></p>
                </div>

                <!-- Intervinientes -->
                <div class="mb-3">
                    <h6 class="fw-bold text-primary mb-2"><i class="bi bi-people me-1"></i>Intervinientes:</h6>
                    <div class="mov-data" id="prev_intervinientes">
                        <!-- Se inyecta la tabla AJAX -->
                    </div>
                </div>

                <!-- Observaciones / Nota Marginal -->
                <div class="mb-2">
                    <h6 class="fw-bold text-primary mb-1"><i class="bi bi-journal-text me-1"></i>Observaciones / Nota Marginal:</h6>
                    <div class="p-3 bg-light rounded border fs-7 text-secondary" id="prev_obs" style="white-space: pre-wrap;"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-pencil me-1"></i>Corregir / Editar
                </button>
                <button type="button" id="btnConfirmarGuardar" class="btn btn-success">
                    <i class="bi bi-check-circle me-1"></i>Guardar Definitivamente
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    // 1. Clonación dinámica de filas basada en los datos de Laravel
    const btnAgregar = document.getElementById('btnAgregarInterviniente');
    const contenedor = document.getElementById('contenedor-intervinientes');

    btnAgregar.addEventListener('click', function() {
        const primeraFila = contenedor.querySelector('.fila-interviniente');
        const nuevaFila = primeraFila.cloneNode(true);

        // Limpiar valores de los inputs clonados
        nuevaFila.querySelectorAll('input').forEach(input => input.value = '');
        nuevaFila.querySelectorAll('select').forEach(select => select.selectedIndex = 0);

        contenedor.appendChild(nuevaFila);
    });

    // 2. Control de eliminar filas
    window.eliminarFila = function(btn) {
        const filas = contenedor.querySelectorAll('.fila-interviniente');
        if (filas.length > 1) {
            btn.closest('.fila-interviniente').remove();
        } else {
            alert("Debe registrar al menos un interviniente para el movimiento.");
        }
    };

    // 3. Petición AJAX para la Previsualización
    $(document).off('click', '#btnPreview').on('click', '#btnPreview', function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();

        let formData = $('#formMovimiento').serialize();

        $.ajax({
            url: '{{ route("movimientos.preview") }}',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                // Asignación de datos al modal (AGREGADA LA LÍNEA DEL TOMO)
                $('#prev_tom').text(response.num_tom || $('input[name="num_tom"]').val() || 'N/A'); 
                $('#prev_rep').text(response.num_repertorio || 'N/A');
                $('#prev_ins').text(response.num_inscripcion || 'N/A');
                $('#prev_fec').text(response.fecha_inscripcion || 'N/A');
                $('#prev_acto').text(response.tipo_acto || 'N/A');
                $('#prev_obs').text(response.observacion || 'Sin observaciones.');
                
                $('#prev_intervinientes').html(response.html_intervinientes);

                // Desplegar Modal Bootstrap 5
                let modalElem = document.getElementById('modalPreview');
                let modalInstance = bootstrap.Modal.getInstance(modalElem) || new bootstrap.Modal(modalElem);
                modalInstance.show();
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    let mensaje = 'Por favor completa los siguientes campos obligatorios:\n\n';
                    $.each(errors, function(key, val) {
                        mensaje += '• ' + val[0] + '\n';
                    });
                    alert(mensaje);
                } else {
                    alert('Error al generar la vista previa. Revisa la consola.');
                    console.error(xhr.responseText);
                }
            }
        });
    });

    // 4. Confirmar Guardado desde la Modal
    $('#btnConfirmarGuardar').on('click', function() {
        let modalElem = document.getElementById('modalPreview');
        let modalInstance = bootstrap.Modal.getInstance(modalElem);
        if (modalInstance) modalInstance.hide();
        
        $('#formMovimiento').submit();
    });

});
</script>
@endpush