@extends('layouts.plantilla01')

@section('titulo', 'Lista de usuarios')

@section('estilos')
<style>
    .tabla-usuarios {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        font-size: 13px;
        background: #ffffff;
        border-radius: 12px;
        overflow: hidden;
    }

    .tabla-usuarios th,
    .tabla-usuarios td {
        padding: 10px;
        text-align: left;
        border-right: 1px solid #e2e8f0;
    }

    .tabla-usuarios th:last-child,
    .tabla-usuarios td:last-child {
        border-right: none;
    }

    .tabla-usuarios th {
        background: linear-gradient(145deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-weight: 500;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 0.8px;
    }

    .tabla-usuarios tr:nth-child(odd) {
        background-color: #f7fafc;
    }

    .tabla-usuarios tr:nth-child(even) {
        background-color: #edf2f7;
    }

    .tabla-usuarios tr:hover {
        background-color: #e6fffa;
        transform: scale(1.01);
        transition: all 0.3s ease;
    }

    .tabla-usuarios td {
        color: #4a5568;
        vertical-align: middle;
    }

    .tabla-usuarios td:last-child {
        text-align: center;
        min-width: 120px;
    }

    .tabla-usuarios button {
        margin: 2px;
        padding: 6px 10px;
        font-size: 11px;
        border: 2px solid transparent;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .tabla-usuarios button a {
        text-decoration: none;
        color: inherit;
        display: block;
    }

    .tabla-usuarios button:first-child {
        background: linear-gradient(135deg, #48bb78, #38a169);
        color: white;
    }

    .tabla-usuarios button:first-child:hover {
        background: linear-gradient(135deg, #38a169, #2f855a);
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(72, 187, 120, 0.3);
    }

    .tabla-usuarios button:last-child {
        background: linear-gradient(135deg, #f56565, #e53e3e);
        color: white;
    }

    .tabla-usuarios button:last-child:hover {
        background: linear-gradient(135deg, #e53e3e, #c53030);
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(245, 101, 101, 0.3);
    }

    /* botón añadir usuario */
    .acciones-superiores {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 15px;
    }

    .btn-add {
        background: linear-gradient(135deg, #4299e1, #3182ce);
        color: white;
        padding: 8px 14px;
        border-radius: 6px;
        font-size: 13px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .btn-add:hover {
        background: linear-gradient(135deg, #3182ce, #2b6cb0);
        box-shadow: 0 4px 8px rgba(66, 153, 225, 0.3);
        transform: translateY(-1px);
    }
</style>
@endsection('estilos')

@section('cuerpo')
<div class="container">
    <h1>Lista de usuarios</h1>

    <div class="acciones-superiores">
        <a href="{{ miurl('añadirusuario') }}" class="btn-add">➕ Añadir usuario</a>
    </div>

    <table class="tabla-usuarios">
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($usuarios as $usuario)
            <tr>
                <td>{{ $usuario['id'] }}</td>
                <td>{{ $usuario['usuario'] }}</td>
                <td>{{ $usuario['rol'] }}</td>
                <td>
                    @if($_SESSION['rol'] == 'administrador')
                    <button><a href="{!! miurl('editarusuario/' . $usuario['id']) !!}">Editar</a></button>
                    <button><a href="{!! miurl('eliminarusuario/' . $usuario['id']) !!}">Eliminar</a></button>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection('cuerpo')