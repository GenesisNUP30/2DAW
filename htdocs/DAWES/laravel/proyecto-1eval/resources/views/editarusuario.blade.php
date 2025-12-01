@extends('layouts.plantilla01')

@section('titulo', 'Editar usuario')

@section('cuerpo')

<h2>Editar usuario</h2>

<form method="POST" action="{{ miurl('editarusuario/' . $id) }}">
    <div class="form-group">
        <label>Nombre de usuario</label>
        <input type="text" name="usuario_nuevo" class="form-control" value="{{ $usuario_nuevo ?? '' }}">

        {!! \App\Models\Funciones::verErrores('usuario_nuevo') !!}
    </div>

    <div class="form-group">
        <label>Contraseña actual (solo si desea cambiarla):</label>
        <input type="password" name="password_antigua" class="form-control">
        {!! \App\Models\Funciones::verErrores('password_antigua') !!}
    </div>

    <div class="form-group">
        <label>Nueva contraseña: </label>
        <input type="password" name="password_nueva" class="form-control">
        {!! \App\Models\Funciones::verErrores('password_nueva') !!}
    </div>

    <div class="form-group">
        <label>Confirmar nueva contraseña:</label>
        <input type="password" name="password_nueva2" class="form-control">
        {!! \App\Models\Funciones::verErrores('password_nueva2') !!}
    </div>

    @if($rol_logueado == 'administrador')
    <div class="form-group">
        <label>Rol</label>
        <select name="rol_nuevo" class="form-control">
            <option value="">Seleccione un rol</option>
            <option value="administrador" @if($rol_nuevo=='administrador') selected @endif>Administrador</option>
            <option value="operario" @if($rol_nuevo=='operario') selected @endif>Operario</option>
        </select>
        {!! \App\Models\Funciones::verErrores('rol_nuevo') !!}
    </div>
    @endif

    <button type="submit" class="btn btn-primary">Actualizar</button>
    <a href="{{ miurl('/') }}" class="btn btn-secondary">Cancelar</a>
    
</form>

@endsection
