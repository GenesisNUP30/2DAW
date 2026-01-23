@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Usuario: {{ $usuario->name }}</h1>

    <form method="POST" action="{{ route('usuarios.update', $usuario) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $usuario->name) }}" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $usuario->email) }}" required>
        </div>
        <div class="mb-3">
            <label>Nueva contraseña (opcional)</label>
            <input type="password" name="password" class="form-control">
        </div>
        <div class="mb-3">
            <label>Confirmar nueva contraseña</label>
            <input type="password" name="password_confirmation" class="form-control">
        </div>
        <div class="mb-3">
            <label>Rol</label>
            <select name="tipo" class="form-select" required>
                <option value="administrador" {{ old('tipo', $usuario->tipo) == 'administrador' ? 'selected' : '' }}>Administrador</option>
                <option value="operario" {{ old('tipo', $usuario->tipo) == 'operario' ? 'selected' : '' }}>Operario</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Actualizar</button>
        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection