@extends('layouts.plantilla01')

@section('titulo', 'Pagina principal')
@section('estilos')
<style>
    .tabla-tareas {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        font-size: 13px;
        background: #ffffff;
        border: 2px solid #cbd5e0;
        border-radius: 12px;
        overflow: hidden;
    }

    .tabla-tareas th,
    .tabla-tareas td {
        padding: 10px;
        text-align: left;
        border-right: 1px solid #e2e8f0;
    }

    .tabla-tareas th:last-child,
    .tabla-tareas td:last-child {
        border-right: none;
    }

    .tabla-tareas th {
        background: linear-gradient(145deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-weight: 500;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 0.8px;
    }

    .tabla-tareas tr:nth-child(odd) {
        background-color: #f7fafc;
    }

    .tabla-tareas tr:nth-child(even) {
        background-color: #edf2f7;
    }

    .tabla-tareas tr:hover {
        background-color: #e6fffa;
        transform: scale(1.01);
        transition: all 0.3s ease;
    }

    .tabla-tareas td {
        color: #4a5568;
        vertical-align: middle;
    }

    .tabla-tareas td:last-child {
        text-align: center;
        min-width: 120px;
    }

    .tabla-tareas button {
        margin: 2px;
        padding: 6px 10px;
        font-size: 11px;
        border: 2px solid transparent;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .tabla-tareas button a {
        text-decoration: none;
        color: inherit;
        display: block;
    }

    .tabla-tareas button:first-child {
        background: linear-gradient(135deg, #48bb78, #38a169);
        color: white;
    }

    .tabla-tareas button:first-child:hover {
        background: linear-gradient(135deg, #38a169, #2f855a);
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(72, 187, 120, 0.3);
    }

    .tabla-tareas button:last-child {
        background: linear-gradient(135deg, #f56565, #e53e3e);
        color: white;
    }

    .tabla-tareas button:last-child:hover {
        background: linear-gradient(135deg, #e53e3e, #c53030);
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(245, 101, 101, 0.3);
    }
</style>
@endsection('estilos')

@section('cuerpo')
<div class="container">
    <h1>Gestión de Tareas</h1>

    <a href="{{ url('alta') }}">Crear Nueva Tarea</a>

    <table class="tabla-tareas">
        <thead>
            <tr>
                <th>ID</th>
                <th>Persona de contacto</th>
                <th>Teléfono</th>
                <th>Correo</th>
                <th>Descripción</th>
                <th>Estado</th>
                <th>Operario</th>
                <th>Fecha Realización</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tareas as $tarea)
            <tr>
                <td>{{ $tarea['id'] }}</td>
                <td>{{ $tarea['persona_contacto'] }}</td>
                <td>{{ $tarea['telefono'] }}</td>
                <td>{{ $tarea['correo'] }}</td>
                <td>{{ $tarea['descripcion'] }}</td>
                <td>{{ $tarea['estado'] }}</td>
                <td>{{ $tarea['operario_encargado'] }}</td>
                <td>{{ $tarea['fecha_realizacion'] }}</td>
                <td>
                    <button><a href="{!! url('modificar/' . $tarea['id']) !!}">Modificar</a></button>
                    <button><a href="{!! url('eliminar/' . $tarea['id']) !!}">Eliminar</a></button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection('cuerpo')