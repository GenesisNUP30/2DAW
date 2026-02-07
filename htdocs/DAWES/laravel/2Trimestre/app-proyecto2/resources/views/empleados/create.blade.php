@extends('layouts.app')

@section('titulo', 'Añadir empleado')

@section('content')
<div class="container">

    <h1 class="mb-4">
        <i class="fas fa-user-plus me-2"></i>Añadir Empleado
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

    <form method="POST" action="{{ route('empleados.store') }}">
        @csrf

        {{-- DNI --}}
        <div class="mb-3">
            <label class="form-label">DNI</label>
            <input type="text" name="dni" class="form-control" value="{{ old('dni') }}">
        </div>

        {{-- NOMBRE --}}
        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}">
        </div>

        {{-- EMAIL --}}
        <div class="mb-3">
            <label class="form-label">Correo electrónico</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}">
        </div>

        {{-- TELÉFONO --}}
        <div class="mb-3">
            <label class="form-label">Teléfono</label>
            <input type="text" name="telefono" class="form-control" value="{{ old('telefono') }}">
        </div>

        {{-- DIRECCIÓN --}}
        <div class="mb-3">
            <label class="form-label">Dirección</label>
            <input type="text" name="direccion" class="form-control" value="{{ old('direccion') }}">
        </div>

        {{-- FECHA DE ALTA --}}
        <div class="mb-3">
            <label class="form-label">Fecha de alta</label>
            <input type="date" name="fecha_alta" class="form-control" value="{{ old('fecha_alta') }}">
        </div>

        {{-- CONTRASEÑA --}}
        <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <input type="password" name="password" class="form-control" value="{{ old('password') }}">
        </div>

        {{-- CONFIRMAR CONTRASEÑA --}}
        <div class="mb-3">
            <label class="form-label">Confirmar contraseña</label>
            <input type="password" name="password_confirmation" class="form-control" value="{{ old('password_confirmation') }}">
        </div>

        {{-- TIPO --}}
        <div class="mb-3">
            <label class="form-label">Tipo</label>
            <select name="tipo" class="form-select">
                <option value="">-- Selecciona tipo --</option>
                <option value="administrador" {{ old('tipo') == 'administrador' ? 'selected' : '' }}>Administrador</option>
                <option value="operario" {{ old('tipo') == 'operario' ? 'selected' : '' }}>Operario</option>
            </select>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary mb-3">
                <i class="fas fa-user-plus me-1"></i> Crear empleado
            </button>

            <a href="{{ route('empleados.index') }}" class="btn btn-secondary mb-3">
                <i class="fas fa-times me-1"></i>
                Cancelar
            </a>
        </div>
    </form>

</div>
@endsection