@extends('layouts.app')

@section('titulo', 'Nueva Cuota Excepcional')

@section('content')
<div class="container py-4">

    {{-- Cabecera --}}
    <div class="row justify-content-center mb-4">
        <div class="col-lg-10">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h2 class="fw-bold m-0 text-dark">
                        <i class="fas fa-file-invoice-dollar me-2"></i>Nueva Cuota Excepcional
                    </h2>
                    <p class="text-muted small mb-0">Registra un cargo puntual fuera de la remesa mensual ordinaria.</p>
                </div>
                <a href="{{ route('cuotas.index') }}" class="btn btn-sm btn-light border shadow-sm">
                    <i class="fas fa-arrow-left me-1"></i> Volver
                </a>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <form action="{{ route('cuotas.store') }}" method="POST">
                @csrf

                <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                    <div class="card-body p-4">

                        {{-- SECCIÓN 1: DETALLES DEL CARGO --}}
                        <h5 class="fw-bold mb-4 text-primary border-bottom pb-2">
                            <i class="fas fa-info-circle me-2"></i>Detalles del Cargo
                        </h5>

                        <div class="row g-3 mb-4">
                            {{-- Cliente --}}
                            <div class="col-md-12">
                                <label class="form-label small fw-bold text-muted text-uppercase">Cliente beneficiario</label>
                                <select name="cliente_id" class="form-select @error('cliente_id') is-invalid @enderror">
                                    <option value="" selected disabled>-- Selecciona el cliente --</option>
                                    @foreach ($clientes as $cliente)
                                    <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                        {{ $cliente->nombre }} ({{ $cliente->cif }})
                                    </option>
                                    @endforeach
                                </select>
                                @error('cliente_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Concepto --}}
                            <div class="col-md-12">
                                <label class="form-label small fw-bold text-muted text-uppercase">Concepto del cargo</label>
                                <input type="text" name="concepto" class="form-control @error('concepto') is-invalid @enderror"
                                    value="{{ old('concepto') }}" placeholder="Ej: Venta de material informático / Configuración servidor">
                                @error('concepto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Fechas --}}
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase">Fecha de emisión</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="far fa-calendar-alt text-muted"></i></span>
                                    <input type="date" name="fecha_emision" class="form-control @error('fecha_emision') is-invalid @enderror"
                                        value="{{ old('fecha_emision', date('Y-m-d')) }}">
                                    @error('fecha_emision') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase">Fecha de pago (Opcional)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-check-circle text-success"></i></span>
                                    <input type="date" name="fecha_pago" class="form-control @error('fecha_pago') is-invalid @enderror"
                                        value="{{ old('fecha_pago') }}">
                                    @error('fecha_pago') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- SECCIÓN 2: IMPORTE Y NOTAS --}}
                        <h5 class="fw-bold mb-4 text-primary border-bottom pb-2">
                            <i class="fas fa-cash-register me-2"></i>Cuantía y Observaciones
                        </h5>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted text-uppercase">Importe Total</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" name="importe" class="form-control form-control-lg fw-bold @error('importe') is-invalid @enderror"
                                        value="{{ old('importe') }}" placeholder="0,00">
                                    <span class="input-group-text bg-white fw-bold">€</span>
                                    @error('importe') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="col-md-8">
                                <label class="form-label small fw-bold text-muted text-uppercase">Notas internas</label>
                                <textarea name="notas" class="form-control @error('notas') is-invalid @enderror"
                                    rows="2" placeholder="Observaciones ...">{{ old('notas') }}</textarea>
                                @error('notas') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                    </div>

                    {{-- Footer con acciones --}}
                    <div class="card-footer bg-light p-4 border-0 text-end" style="border-radius: 0 0 15px 15px;">
                        <a href="{{ route('cuotas.index') }}" class="btn btn-light border px-4 me-2">
                            <i class="fas fa-times me-1"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">
                            <i class="fas fa-save me-2"></i>Guardar Cuota
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection