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
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .tabla-tareas th,
    .tabla-tareas td {
        padding: 12px 15px;
        text-align: left;
    }


    .tabla-tareas th {
        background: #2c3e50;
        color: white;
        font-weight: 600;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }

    .tabla-tareas tr:nth-child(even) {
        background-color: #f8f9fa;
    }

    .tabla-tareas tr:hover {
        background-color: #edf2ff;
    }

    .tabla-tareas td {
        color: #4a5568;
        vertical-align: middle;
    }

    .tabla-tareas td:last-child {
        text-align: center;
        min-width: 130px;
    }

    .tabla-tareas .btn {
        margin: 3px;
        padding: 6px 12px;
        font-size: 11px;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-block;
        color: white;
    }

    .tabla-tareas .btn-detalle {
        background: linear-gradient(135deg, #3182ce, #2b6cb0);
    }

    .tabla-tareas .btn-detalle:hover {
        background: linear-gradient(135deg, #2b6cb0, #2c5282);
        transform: translateY(-2px);
        box-shadow: 0 3px 6px rgba(49, 130, 206, 0.3);
    }

    .tabla-tareas .btn-editar {
        background: linear-gradient(135deg, #48bb78, #38a169);
    }

    .tabla-tareas .btn-editar:hover {
        background: linear-gradient(135deg, #38a169, #2f855a);
        transform: translateY(-2px);
        box-shadow: 0 3px 6px rgba(72, 187, 120, 0.3);
    }

    .tabla-tareas .btn-eliminar {
        background: linear-gradient(135deg, #f56565, #e53e3e);
    }

    .tabla-tareas .btn-eliminar:hover {
        background: linear-gradient(135deg, #e53e3e, #c53030);
        transform: translateY(-2px);
        box-shadow: 0 3px 6px rgba(245, 101, 101, 0.3);
    }

    .tabla-tareas .btn-completar {
        background: linear-gradient(135deg, #3182ce, #2b6cb0);
    }

    .tabla-tareas .btn-completar:hover {
        background: linear-gradient(135deg, #2b6cb0, #2c5282);
        transform: translateY(-2px);
        box-shadow: 0 3px 6px rgba(49, 130, 206, 0.3);
    }
</style>
@endsection('estilos')

@section('cuerpo')
<div class="container">
    <h1 class="mb-4">
        <i class="fas fa-tasks me-2"></i>Gestión de Tareas
    </h1>
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
                <td>{{ \App\Models\Funciones::cambiarFormatoFecha($tarea['fecha_realizacion']) }}</td>
                <td>
                    @if($_SESSION['rol'] == 'administrador')
                    <a href="{!! miurl('tarea/' . $tarea['id']) !!}" class="btn btn-detalle">
                        <i class="fas fa-eye me-1"></i>Ver más
                    </a>
                    <a href="{!! miurl('modificar/' . $tarea['id']) !!}" class="btn btn-editar">
                        <i class="fas fa-edit me-1"></i>Modificar
                    </a>
                    <a href="{!! miurl('eliminar/' . $tarea['id']) !!}" class="btn btn-eliminar">
                        <i class="fas fa-trash-alt me-1"></i>Eliminar
                    </a>
                    @endif
                    @if($_SESSION['rol'] == 'operario')
                    <a href="{!! miurl('completar/' . $tarea['id']) !!}" class="btn btn-completar">
                        <i class="fas fa-check-circle me-1"></i>Completar
                    </a>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection('cuerpo')