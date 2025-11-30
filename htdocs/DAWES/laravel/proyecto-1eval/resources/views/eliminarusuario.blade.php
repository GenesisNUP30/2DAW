@extends('layouts.plantilla01')

@section('titulo', 'Eliminar Tarea')

@section('cuerpo')
   <h1>Eliminar Usuario</h1>
   <p>¿Estás seguro de que deseas eliminar el usuario <strong>{{ $usuario['usuario'] }}</strong>?</p>
   <form action="{{ url('eliminarusuario/' . $usuario['id']) }}" method="POST">
        <input type="submit" value="Sí, eliminar">
        <a href="{{ url('/') }}">Cancelar</a>
   </form>
@endsection