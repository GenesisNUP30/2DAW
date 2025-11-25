@extends('layouts.plantilla01')

@section('titulo', 'Eliminar Tarea')

@section('cuerpo')
   <h1>Eliminar Tarea</h1>

   <p>¿Estás seguro de que deseas eliminar la tarea de <strong>{{ $tarea['persona_contacto'] }}</strong>?</p>

   <form action="{{ url('eliminar/' . $tarea['id']) }}" method="POST">
        <input type="submit" value="Sí, eliminar">
        <a href="{{ url('/') }}">Cancelar</a>
   </form>
@endsection