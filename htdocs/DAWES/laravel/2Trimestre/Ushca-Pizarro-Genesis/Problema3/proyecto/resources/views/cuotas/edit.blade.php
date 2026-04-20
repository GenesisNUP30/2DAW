@extends('layouts.app')

@section('titulo', 'Editar cuota')

@section('content')
<div class="container py-4">

    {{-- Cabecera --}}
    <div class="row justify-content-center mb-4">
        <div class="col-lg-8">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h2 class="fw-bold m-0 text-dark">
                        <i class="fas fa-edit me-2"></i>Editar Cuota <span class="text-muted">ID: {{ $cuota->id }}</span>
                    </h2>
                    <p class="text-muted small mb-0">Actualiza los términos económicos o el estado de pago de la cuota.</p>
                </div>
                <a href="{{ route('cuotas.index') }}" class="btn btn-sm btn-light border shadow-sm px-3">
                    <i class="fas fa-arrow-left me-1"></i> Volver
                </a>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <form action="{{ route('cuotas.update', $cuota) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                    <div class="card-body p-4">

                        {{-- SECCIÓN 1: ASIGNACIÓN Y CONCEPTO --}}
                        <h5 class="fw-bold mb-4 text-success border-bottom pb-2">
                            <i class="fas fa-file-invoice-dollar me-2"></i>Información General
                        </h5>

                        <div class="row g-3 mb-4">
                            <div class="col-md-12">
                                <label class="form-label small fw-bold text-muted text-uppercase">Cliente Responsable</label>
                                <select name="cliente_id" class="form-select @error('cliente_id') is-invalid @enderror">
                                    <option value="" disabled>-- Selecciona un cliente --</option>
                                    @foreach ($clientes as $cliente)
                                    <option value="{{ $cliente->id }}" {{ old('cliente_id', $cuota->cliente_id) == $cliente->id ? 'selected' : '' }}>
                                        {{ $cliente->nombre }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('cliente_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label small fw-bold text-muted text-uppercase">Concepto de la cuota</label>
                                <input type="text" name="concepto" class="form-control @error('concepto') is-invalid @enderror"
                                    value="{{ old('concepto', $cuota->concepto) }}" placeholder="Ej: Mantenimiento Mensual">
                                @error('concepto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- SECCIÓN 2: ECONOMÍA Y FECHAS --}}
                        <h5 class="fw-bold mb-4 text-success border-bottom pb-2">
                            <i class="fas fa-calendar-check me-2"></i>Importe y Plazos
                        </h5>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase">Importe</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" name="importe" class="form-control @error('importe') is-invalid @enderror"
                                        value="{{ old('importe', $cuota->importe) }}">
                                    <span class="input-group-text bg-light fw-bold text-muted">€</span>
                                    @error('importe') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase">Fecha de Emisión</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-calendar-alt text-muted"></i></span>
                                    <input type="date" name="fecha_emision" class="form-control @error('fecha_emision') is-invalid @enderror"
                                        value="{{ old('fecha_emision', $cuota->fecha_emision ? $cuota->fecha_emision->format('Y-m-d') : '') }}">
                                    @error('fecha_emision') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label small fw-bold text-muted text-uppercase">Fecha de Pago</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-success"><i class="fas fa-check-circle"></i></span>
                                    <input type="date" name="fecha_pago" class="form-control @error('fecha_pago') is-invalid @enderror"
                                        value="{{ old('fecha_pago', $cuota->fecha_pago ? $cuota->fecha_pago->format('Y-m-d') : '') }}">
                                    @error('fecha_pago') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="form-text text-muted small">Dejar vacío si la cuota aún está pendiente de cobro.</div>
                            </div>
                        </div>

                        {{-- SECCIÓN 3: NOTAS ADICIONALES --}}
                        <h5 class="fw-bold mb-4 text-success border-bottom pb-2">
                            <i class="fas fa-sticky-note me-2"></i>Observaciones
                        </h5>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label small fw-bold text-muted text-uppercase">Notas internas</label>
                                <textarea name="notas" class="form-control @error('notas') is-invalid @enderror" rows="3"
                                    placeholder="Anotaciones sobre el cobro o incidencias...">{{ old('notas', $cuota->notas) }}</textarea>
                                @error('notas') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                    </div>
                    <div class="card-footer bg-light p-4 border-0 text-end" style="border-radius: 0 0 15px 15px;">
                        <a href="{{ route('cuotas.index') }}" class="btn btn-light border px-4 me-2">
                            <i class="fas fa-times me-1"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-success px-5 fw-bold shadow-sm text-white">
                            <i class="fas fa-save me-2"></i>Actualizar Cuota
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection