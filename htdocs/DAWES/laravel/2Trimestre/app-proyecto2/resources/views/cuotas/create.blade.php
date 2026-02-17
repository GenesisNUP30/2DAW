@extends('layouts.app')

@section('titulo', 'Añadir nueva cuota')

@section('content')
<div class="container">
    <h1 class="mb-4">
        <i class="fas fa-wallet me-1"></i> Añadir nueva cuota
    </h1>

    {{-- ERRORES --}}
    @if ($errors->any())
    <div class="alert alert-danger">
        <strong>Por favor, corrige los siguientes errores:</strong>
    </div>
    @endif

    <form action="{{ route('cuotas.store') }}" method="POST">
        @csrf

        {{-- CLIENTE --}}
        <div class="mb-3">
            <label class="form-label">Cliente</label>
            <select name="cliente_id" class="form-select">
                <option value="">-- Selecciona un cliente --</option>
                @foreach ($clientes as $cliente)
                <option value="{{ $cliente->id }}" {{ old('cliente_id', $cuota->cliente_id ?? '') === $cliente->id ? 'selected' : '' }}>
                    {{ $cliente->nombre }}
                </option>
                @endforeach
            </select>
            @error('cliente_id')
            <div class="text-danger mt-1">{{ $message }}</div>
            @enderror

        {{-- CONCEPTO --}}
        <div class="mb-3">
            <label class="form-label">Concepto</label>
            <input type="text" name="concepto" class="form-control" value="{{ old('concepto') }}">
            @error('concepto')
            <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
            <small class="form-text text-muted">
                Ej: Compra de producto
            </small>
        </div>

        {{-- FECHA EMISION --}}
        <div class="mb-3">
            <label class="form-label">Fecha de emisión</label>
            <input type="date" name="fecha_emision" class="form-control" value="{{ old('fecha_emision') }}">
            @error('fecha_emision')
            <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- IMPORTE --}}
        <div class="mb-3">
            <label class="form-label">Importe</label>
            <input type="number" name="importe" class="form-control" value="{{ old('importe') }}">
            @error('importe')
            <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- FECHA PAGO --}}
        <div class="mb-3">
            <label class="form-label">Fecha de pago</label>
            <input type="date" name="fecha_pago" class="form-control" value="{{ old('fecha_pago') }}">
            @error('fecha_pago')
            <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- NOTAS --}}
        <div class="mb-3">
            <label class="form-label">Notas</label>
            <textarea name="notas" class="form-control" rows="3">{{ old('notas') }}</textarea>
            @error('notas')
            <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary mb-3">
                <i class="fas fa-wallet me-1"></i> Guardar cuota
            </button>

            <a href="{{ route('cuotas.index') }}" class="btn btn-secondary mb-3">
                <i class="fas fa-times me-1"></i>
                Cancelar
            </a>
        </div>
    </form>

</div>
@endsection