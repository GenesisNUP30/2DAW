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

    {{-- Tabla de empleados --}}
    <div class="col-md-10">

        {{-- Botón crear empleado (solo administrador) --}}
        @if (auth()->user()->isAdmin())
        <a href="{{ route('empleados.create') }}" class="btn btn-primary mb-3">
            <i class="fas fa-user-plus me-2"></i> Añadir empleado
        </a>
        @endif

        <div class="card">
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Tipo</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($empleados as $usu)
                        <tr>
                            <td>{{ $usu->id }}</td>
                            <td>{{ $usu->name }}</td>
                            <td>{{ $usu->email }}</td>
                            <td>{{ $usu->tipo }}</td>
                            <td class="text-end">
                                <a href="{{ route('empleados.edit', $usu) }}" class="btn btn-sm btn-warning">
                                    Editar
                                </a>

                                <a href="{{ route('empleados.confirmDelete', $usu) }}"
                                    class="btn btn-sm btn-danger">
                                    Eliminar
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>


</div>
@endsection