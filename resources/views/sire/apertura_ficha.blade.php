@extends('layouts.base')

@section('title', 'Sistema Sire - Apertura Ficha Registral')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="m-0"><i class="bi bi-file-earmark-plus me-2"></i>Apertura de Nueva Ficha Registral (Predio Nuevo)</h5>
            <span class="badge bg-primary fs-6">SDB-{{ $nuevoNumFicha }}</span>
        </div>
        <div class="card-body">
            
            <form action="{{ route('fichas.store') }}" method="POST">
                @csrf

                <div class="alert alert-info py-2 small fw-semibold">
                    <i class="bi bi-info-circle me-1"></i> INFORMACIÓN REGISTRAL INICIAL
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Fecha de Apertura</label>
                        <input type="datetime-local" name="fichfecape" class="form-control" value="{{ date('Y-m-d\TH:i') }}" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Tipo de Predio</label>
                        <select name="fichtippre" class="form-select" required>
                            <option value="R" selected>R - Rural</option>
                            <option value="U">U - Urbano</option>
                        </select>
                    </div>

                    <div class="col-md-3">
    <label class="form-label fw-semibold text-secondary">Cantón / Parroquia:</label>
    <select name="fichcodpar" class="form-select" required>
        <option value="">Seleccione...</option>
        @foreach($cantones as $canton)
            <option value="{{ $canton->cantcodcan }}" 
                {{ $canton->cantnombre == 'MORONA' || $canton->cantnombre == 'SEVILLA DON BOSCO' ? 'selected' : '' }}>
                {{ $canton->cantnombre }}
            </option>
        @endforeach
    </select>
</div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Cód. Catastral</label>
                        <input type="text" name="fichcodigo" class="form-control font-monospace" placeholder="XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Sector / Ubicación</label>
                        <input type="text" name="sector" class="form-control" value="Sevilla Don Bosco" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Cantón</label>
                        <input type="text" name="canton" class="form-control" value="cantón Morona" required>
                    </div>
                </div>

                <div class="alert alert-secondary py-2 small fw-semibold">
                    <i class="bi bi-compass me-1"></i> LINDEROS REGISTRALES
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-dark">NORTE:</label>
                        <textarea name="norte" class="form-control" rows="2" placeholder="Ej: Lote de Ojeda Awananch Lárazo Mariano en 70,30 m, RS89-42-3E" required></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold text-dark">SUR:</label>
                        <textarea name="sur" class="form-control" rows="2" placeholder="Ej: Lote de Ojeda Awananch Ninfa Balbina en 106,77 m, RS89-53-10W" required></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold text-dark">ESTE:</label>
                        <textarea name="este" class="form-control" rows="2" placeholder="Ej: Quebrada Tiin Entza, siguiendo su curso en 47,99m" required></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold text-dark">OESTE:</label>
                        <textarea name="oeste" class="form-control" rows="2" placeholder="Ej: Calle Domingo Comin en 25,07 m, RN1-14-28W" required></textarea>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-bold text-success">ÁREA TOTAL:</label>
                        <input type="text" name="area_total" class="form-control" placeholder="Ej: 0,2344 hectáreas" required>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-success px-4">
                        <i class="bi bi-plus-circle me-1"></i> Guardar Ficha e Inscribir Movimiento
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection