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
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .tabla-usuarios th,
    .tabla-usuarios td {
        padding: 12px 15px;
        text-align: left;
    }

    .tabla-usuarios th {
        background: #2c3e50;
        color: white;
        font-weight: 600;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }

    .tabla-usuarios tr:nth-child(even) {
        background-color: #f8f9fa;
    }

    .tabla-usuarios tr:hover {
        background-color: #edf2ff;
    }

    .tabla-usuarios td {
        color: #4a5568;
        vertical-align: middle;
    }

    .tabla-usuarios td:last-child {
        text-align: center;
        min-width: 130px;
    }

    .tabla-usuarios .btn {
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

    .tabla-usuarios .btn-editar {
        background: linear-gradient(135deg, #48bb78, #38a169);
    }

    .tabla-usuarios .btn-editar:hover {
        background: linear-gradient(135deg, #38a169, #2f855a);
        transform: translateY(-2px);
        box-shadow: 0 3px 6px rgba(72, 187, 120, 0.3);
    }

    .tabla-usuarios .btn-eliminar {
        background: linear-gradient(135deg, #f56565, #e53e3e);
    }

    .tabla-usuarios .btn-eliminar:hover {
        background: linear-gradient(135deg, #e53e3e, #c53030);
        transform: translateY(-2px);
        box-shadow: 0 3px 6px rgba(245, 101, 101, 0.3);
    }

    .acciones-superiores {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 20px;
    }

    .btn-add {
        background: linear-gradient(135deg, #4299e1, #3182ce);
        color: white;
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 13px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-add:hover {
        background: linear-gradient(135deg, #3182ce, #2b6cb0);
        box-shadow: 0 4px 8px rgba(66, 153, 225, 0.3);
        transform: translateY(-2px);
    }
</style>
@endsection

@section('cuerpo')
<div class="container">
    <h1 class="mb-4">
        <i class="fas fa-users me-2"></i>Lista de usuarios
    </h1>

    <div class="acciones-superiores">
        <a href="{{ miurl('añadirusuario') }}" class="btn-add">
            <i class="fas fa-user-plus"></i> Añadir usuario
        </a>
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
                    <a href="{!! miurl('editarusuario/' . $usuario['id']) !!}" class="btn btn-editar">
                        <i class="fas fa-edit me-1"></i>Editar
                    </a>
                    <a href="{!! miurl('eliminarusuario/' . $usuario['id']) !!}" class="btn btn-eliminar">
                        <i class="fas fa-trash-alt me-1"></i>Eliminar
                    </a>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection