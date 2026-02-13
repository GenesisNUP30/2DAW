@extends('layouts.app')

@section('titulo', 'Lista de empleados')

@section('content')

<div class="container">

    <h1 class="mb-4">
        <i class="fas fa-users"></i> Lista de empleados
    </h1>

    {{-- Mensaje de éxito --}}
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    {{-- MENSAJE DE ERROR --}}
    @if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    {{-- Tabla de empleados --}}
    <div class="d-flex justify-content-between mb-3">

        {{-- Botón crear empleado (solo administrador) --}}
        @if (auth()->user()->isAdmin())
        <a href="{{ route('empleados.create') }}" class="btn btn-primary mb-3">
            <i class="fas fa-user-plus me-2"></i> Añadir empleado
        </a>
        @endif

    </div>
    <div class="card">
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($empleados as $empleado)
                    <tr>
                        <td>{{ $empleado->id }}</td>
                        <td>{{ $empleado->name }}</td>
                        <td>{{ $empleado->email }}</td>
                        <td>{{ $empleado->telefono }}</td>
                        <td>{{ $empleado->tipo }}</td>
                        <td>
                            @if ($empleado->isBaja())
                            <span class="badge bg-secondary">De baja</span>
                            @else
                            <span class="badge bg-primary">Activo</span>
                            @endif
                        </td>
                        <td class="text-end">
                            @if (!$empleado->isBaja())
                            <a href="{{ route('empleados.edit', $empleado) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i> Editar
                            </a>

                            <a href="{{ route('empleados.confirmBaja', $empleado) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-user-minus"></i> Dar de baja
                            </a>

                            <a href="{{ route('empleados.confirmDelete', $empleado) }}" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i> Eliminar
                            </a>
                            @else
                            <a href="{{ route('empleados.confirmAlta', $empleado) }}" class="btn btn-sm btn-success">
                                <i class="fas fa-user-check"></i> Reactivar
                            </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-3">
                            No hay empleados registrados.
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>



</div>
@endsection