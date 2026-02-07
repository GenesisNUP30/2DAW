@extends('layouts.app')

@section('titulo', 'Editar empleado')

@section('content')
<div class="container">

    <h1 class="mb-4">
        <i class="fas fa-user-edit"></i> Editar Empleado: {{ $empleado->name }}
    </h1>

    {{-- ERRORES --}}
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('empleados.update', $empleado->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- DNI --}}
        <div class="mb-3">
            <label class="form-label">DNI</label>
            <input type="text" name="dni" class="form-control" value="{{ old('dni', $empleado->dni) }}">
            @error('dni')
            <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- NOMBRE --}}
        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $empleado->name) }}">
            @error('name')
            <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- EMAIL --}}
        <div class="mb-3">
            <label class="form-label">Correo electrónico</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $empleado->email) }}">
            @error('email')
            <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- TELEFONO --}}
        <div class="mb-3">
            <label class="form-label">Teléfono</label>
            <input type="tel" name="telefono" class="form-control" value="{{ old('telefono', $empleado->telefono) }}">
            @error('telefono')
            <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- DIRECCION --}}
        <div class="mb-3">
            <label class="form-label">Dirección</label>
            <input type="text" name="direccion" class="form-control" value="{{ old('direccion', $empleado->direccion) }}">
            @error('direccion')
            <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- FECHA DE ALTA --}}
        <div class="mb-3">
            <label class="form-label">Fecha de alta</label>
            <input type="date" name="fecha_alta" class="form-control" value="{{ old('fecha_alta', $empleado->fecha_alta) }}">
            @error('fecha_alta')
            <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- NUEVA CONTRASEÑA --}}
        <div class="mb-3">
            <label class="form-label">Nueva contraseña (opcional)</label>
            <input type="password" name="password" class="form-control" value="{{ old('password') }}">
            @error('password')
            <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- CONFIRMAR NUEVA CONTRASEÑA --}}
        <div class="mb-3">
            <label class="form-label">Confirmar contraseña</label>
            <input type="password" name="password_confirmation" class="form-control" value="{{ old('password_confirmation') }}">
            @error('password_confirmation')
            <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- TIPO --}}
        <div class="mb-3">
            <label class="form-label">Tipo</label>
            <select name="tipo" class="form-select">
                <option value="">-- Selecciona tipo --</option>
                <option value="administrador" {{ old('tipo', $empleado->tipo) == 'administrador' ? 'selected' : '' }}>Administrador</option>
                <option value="operario" {{ old('tipo', $empleado->tipo) == 'operario' ? 'selected' : '' }}>Operario</option>
            </select>
            @error('tipo')
            <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success mb-3">
                <i class="fas fa-sync-alt me-1"></i> Actualizar empleado
            </button>

            <a href="{{ route('empleados.index') }}" class="btn btn-secondary mb-3">
                <i class="fas fa-times me-1"></i>
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection