@extends('layouts.app')

@section('titulo', 'Lista de clientes')

@section('content')
<div class="container">
    <h1 class="mb-4">
        <i class="fas fa-user-plus me-2"></i>Añadir nuevo cliente
    </h1>

    {{-- ERRORES --}}
    @if ($errors->any())
    <div class="alert alert-danger">
        <strong>Por favor, corrige los siguientes errores:</strong>
    </div>
    @endif

    <form action="{{ route('clientes.store') }}" method="post">
        @csrf

        {{-- CIF --}}
        <div class="mb-3">
            <label class="form-label">CIF</label>
            <input type="text" name="cif" class="form-control" value="{{ old('cif') }}">
            @error('cif')
            <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- NOMBRE --}}
        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}">
            @error('nombre')
            <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- TELÉFONO --}}
        <div class="mb-3">
            <label class="form-label">Teléfono</label>
            <input type="text" name="telefono" class="form-control" value="{{ old('telefono') }}">
            @error('telefono')
            <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- CORREO --}}
        <div class="mb-3">
            <label class="form-label">Correo</label>
            <input type="text" name="correo" class="form-control" value="{{ old('correo') }}">
            @error('correo')
            <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- CUENTA CORRIENTE --}}
        <div class="mb-3">
            <label class="form-label">Cuenta corriente</label>
            <input type="text" name="cuenta_corriente" class="form-control" value="{{ old('cuenta_corriente') }}">
            @error('cuenta_bancaria')
            <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- PAIS --}}
        <div class="mb-3">
            <label class="form-label">País</label>
            <select name="pais" class="form-control">
                <option value="">-- Selecciona un país --</option>
                @foreach ($paises as $pais)
                <option value="{{ $pais->iso2 }}" {{ old('pais', $cliente->pais ?? '') === $pais->iso2 ? 'selected' : '' }}>
                    {{ $pais->nombre }}
                </option>
                @endforeach
            </select>
            @error('pais')
            <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- MONEDA --}}
        <div class="mb-3">
            <label class="form-label">Moneda</label>
            <select name="moneda" class="form-control">
                <option value="">-- Selecciona una moneda --</option>
                @foreach ($monedas as $codigo => $nombre)
                <option value="{{ $codigo }}" {{ old('moneda') === $codigo ? 'selected' : '' }}>
                    {{ $nombre }}
                </option>
                @endforeach
            </select>
            @error('moneda')
            <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- IMPORTE CUOTA MENSUAL --}}
        <div class="mb-3">
            <label class="form-label">Importe cuota mensual</label>
            <input type="number" name="importe_cuota_mensual" class="form-control" value="{{ old('importe_cuota_mensual') }}">
            @error('importe_cuota_mensual')
            <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary mb-3">
                <i class="fas fa-user-plus me-1"></i> Guardar cliente
            </button>

            <a href="{{ route('clientes.index') }}" class="btn btn-secondary mb-3">
                <i class="fas fa-times me-1"></i>
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection