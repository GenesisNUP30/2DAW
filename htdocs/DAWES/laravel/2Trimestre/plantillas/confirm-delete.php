@extends('layouts.app')

@section('content')
<div class="container">
    <h1>¿Eliminar usuario?</h1>
    <p>¿Estás seguro de que deseas eliminar al usuario <strong>{{ $usuario->name }}</strong>?</p>

    <form method="POST" action="{{ route('usuarios.destroy', $usuario) }}" style="display:inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Sí, eliminar</button>
    </form>
    <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
</div>
@endsection