@extends('layouts.plantilla01')

@section('titulo', 'Alta de Tarea')
@section('estilos')
<style>
    .error {
        color: red;
    }
</style>
@endsection('estilos')

@section('cuerpo')
<h1>Alta de Tarea</h1>

@include('form')

@endsection