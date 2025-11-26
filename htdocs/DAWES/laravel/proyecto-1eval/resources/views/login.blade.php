@extends('layouts.plantilla01')

@section('titulo', 'Login')
@section('estilos')
<style>
</style>
@endsection('estilos')

@section('cuerpo')
<div class="container">
    <h1>Introduce tus credenciales para iniciar sesión</h1>
    @if (isset($error))
    <div>
        {{ $error }}
    </div>
    @endif
    <form method="post">
        <div>
            <label for="usuario">Usuario</label>
            <input type="text" id="usuario" name="usuario">
        </div>
        <div>
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password">
        </div>
        <button type="submit">Iniciar Sesión</button>
    </form>
</div>
@endsection('cuerpo')