@extends('layouts.plantilla01')

@section('titulo', 'Completar Tarea')

@section('estilos')
<style>
    .error {
        color: red;
    }
</style>
@endsection

@section('cuerpo')
<h1>Completar Tarea</h1>

<form action="{{ url('completar/' . $id) }}" method="POST" enctype="multipart/form-data">
    <label>NIF/CIF:</label><br>
    <input type="text" name="nif_cif" value="{{ $nif_cif }}" readonly><br>
    <br>

    <label>Persona de contacto:</label><br>
    <input type="text" name="persona_contacto" value="{{ $persona_contacto }}" readonly><br>
    <br>

    <label>Descripción de la tarea:</label><br>
    <textarea name="descripcion" readonly>{{ $descripcion }}</textarea><br>
    <br>

    <label>Estado:</label><br>
    <input type="radio" name="estado" value="R" checked> Completada<br>
    <input type="radio" name="estado" value="C" {{ $estado=="C" ? "checked" : "" }}> Cancelada<br>
    <br>
    {!! \App\Models\Funciones::verErrores('estado') !!}

    <label>Fecha de realización:</label><br>
    <input type="date" name="fecha_realizacion" value="{{ $fecha_realizacion }}" readonly><br>
    <br>

    <label for="anotaciones_anteriores">Anotaciones anteriores:</label><br>
    <textarea id="anotaciones_anteriores" name="anotaciones_anteriores" readonly>{{ $anotaciones_anteriores }}</textarea><br><br>

    <label for="anotaciones_posteriores">Anotaciones posteriores:</label><br>
    <textarea id="anotaciones_posteriores" name="anotaciones_posteriores">{{ $anotaciones_posteriores }}</textarea><br><br>
    {!! \App\Models\Funciones::verErrores('anotaciones_posteriores') !!}
    
    <label for="fichero_resumen">Fichero resumen:</label>
    <input type="file" id="fichero_resumen" name="fichero_resumen" multiple><br><br>

    <a class="btn btn-cancel" href="{{ url('/') }}">Cancelar</a>
    <input type="submit" value="Completar tarea">
</form>
@endsection