@extends('layouts.app')

@section('titulo', 'Nueva Cuota Excepcional')

@section('content')
<div class="container py-4">

    {{-- Cabecera --}}
    <div class="row justify-content-center mb-4">
        <div class="col-lg-8">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h2 class="fw-bold m-0 text-dark">
                        <i class="fas fa-file-invoice-dollar me-2 text-primary"></i>Nueva cuota excepcional
                    </h2>
                    <p class="text-muted small mb-0">Registra un cargo puntual fuera de la remesa mensual.</p>
                </div>
                <a href="{{ route('cuotas.index') }}" class="btn btn-sm btn-light border shadow-sm">
                    <i class="fas fa-arrow-left me-1"></i> Volver
                </a>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <form action="{{ route('cuotas.store') }}" method="POST">
                        @csrf

                        <div class="row g-3">
                            {{-- Seleccionar Cliente --}}
                            <div class="col-12">
                                <label class="form-label small fw-bold text-uppercase text-muted">Cliente beneficiario</label>
                                <select name="cliente_id" class="form-select @error('cliente_id') is-invalid @enderror">
                                    <option value="" selected disabled>-- Selecciona el cliente --</option>
                                    @foreach ($clientes as $cliente)
                                    <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                        {{ $cliente->nombre }} ({{ $cliente->cif }})
                                    </option>
                                    @endforeach
                                </select>
                                @error('cliente_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Concepto --}}
                            <div class="col-12">
                                <label class="form-label small fw-bold text-uppercase text-muted">Concepto del cargo</label>
                                <input type="text" name="concepto" class="form-control @error('concepto') is-invalid @enderror"
                                    value="{{ old('concepto') }}" placeholder="Ej: Venta de material informático">
                                @error('concepto')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                <div class="form-text small text-muted">Breve descripción para la factura.</div>
                                @enderror
                            </div>

                            {{-- Fecha Emisión --}}
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-uppercase text-muted">Fecha de emisión</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text bg-light"><i class="far fa-calendar-alt"></i></span>
                                    <input type="date" name="fecha_emision" class="form-control @error('fecha_emision') is-invalid @enderror"
                                        value="{{ old('fecha_emision', date('Y-m-d')) }}">
                                    @error('fecha_emision')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Fecha Pago --}}
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-uppercase text-muted">Fecha de pago (Opcional)</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text bg-light text-success"><i class="fas fa-check-circle"></i></span>
                                    <input type="date" name="fecha_pago" class="form-control @error('fecha_pago') is-invalid @enderror"
                                        value="{{ old('fecha_pago') }}">
                                    @error('fecha_pago')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="form-text text-muted">
                                    Si no hay fecha de pago, deje el campo vacío
                                </small>
                            </div>

                            {{-- Importe --}}
                            <div class="col-12">
                                <div class="bg-light p-3 rounded-3 mt-2 border">
                                    <label class="form-label small fw-bold text-uppercase text-muted">Importe total</label>
                                    <div class="input-group input-group-lg w-50 has-validation">
                                        <input type="text" step="0.01" name="importe" class="form-control fw-bold @error('importe') is-invalid @enderror"
                                            value="{{ old('importe') }}" placeholder="0.00">
                                        <span class="input-group-text bg-white fw-bold text-muted">€</span>
                                        @error('importe')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    @if(!$errors->has('importe'))
                                    <div class="form-text mt-1 text-muted small">Usa punto para los decimales (ej: 45.50).</div>
                                    @endif
                                </div>
                            </div>

                            {{-- Notas --}}
                            <div class="col-12">
                                <label class="form-label small fw-bold text-uppercase text-muted">Notas internas</label>
                                <textarea name="notas" class="form-control @error('notas') is-invalid @enderror"
                                    rows="2" placeholder="Observaciones privadas...">{{ old('notas') }}</textarea>
                                @error('notas')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Botones --}}
                            <div class="col-12 mt-4 text-end">
                                <hr class="opacity-50">
                                <a href="{{ route('cuotas.index') }}" class="btn btn-light border px-4 me-2">
                                    Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">
                                    <i class="fas fa-wallet me-2"></i>Guardar cuota
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection