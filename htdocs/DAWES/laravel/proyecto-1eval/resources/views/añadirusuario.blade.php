@extends('layouts.plantilla01')

@section('titulo', 'Añadir usuario')

@section('cuerpo')

<h2>Añadir usuario</h2>

<form method="POST" action="{{ miurl('añadirusuario') }}">

    <div class="form-group">
        <label>Nombre de usuario</label>
        <input type="text" name="usuario" class="form-control" value="{{ $usuario ?? '' }}">
        {!! \App\Models\Funciones::verErrores('usuario') !!}
    </div>

    <div class="form-group">
        <label>Contraseña</label>
        <input type="password" name="password" class="form-control">
        {!! \App\Models\Funciones::verErrores('password') !!}
    </div>

    <div class="form-group">
        <label>Confirmar contraseña</label>
        <input type="password" name="password2" class="form-control">
        {!! \App\Models\Funciones::verErrores('password2') !!}
    </div>

    <div class="form-group">
        <label>Rol</label>
        <select name="rol" class="form-control">
            <option value="">Seleccione un rol</option>
            <option value="administrador" @if(($rol ?? '')=='administrador') selected @endif>Administrador</option>
            <option value="operario" @if(($rol ?? '')=='operario') selected @endif>Operario</option>
        </select>
        {!! \App\Models\Funciones::verErrores('rol') !!}
    </div>

    <button type="submit" class="btn btn-primary">Crear usuario</button>
    <a href="{{ miurl('/') }}" class="btn btn-secondary">Cancelar</a>

</form>

@endsection
