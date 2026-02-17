@extends('layouts.app')

@section('titulo', 'Lista de clientes')

@section('content')
<div class="container">
    <h1 class="mb-4">
        <i class="fas fa-user-tie"></i> Lista de clientes
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

    {{-- Botones de acción --}}
    <div class="d-flex justify-content-between mb-3">
        {{-- Botón crear cliente (solo administrador) --}}
        @if (auth()->user()->isAdmin())
        <a href="{{ route('clientes.create') }}" class="btn btn-primary mb-3">
            <i class="fas fa-user-plus me-2"></i> Añadir cliente
        </a>
        @endif
    </div>

    {{-- Tabla de clientes --}}
    <div class="card">
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>CIF</th>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>Pais</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($clientes as $cliente)
                    <tr>
                        <td>{{ $cliente->id }}</td>
                        <td>{{ $cliente->cif }}</td>
                        <td>{{ $cliente->nombre }}</td>
                        <td>{{ $cliente->telefono }}</td>
                        <td>{{ $cliente->correo }}</td>
                        <td>{{ $cliente->paisRelacion ? $cliente->paisRelacion->nombre : '—' }}</td>
                        <td class="text-end">
                            @if (!$cliente->isBaja())
                            <a href="{{ route('clientes.show', $cliente) }}" class="btn btn-sm btn-info me-1">
                                <i class="fas fa-eye"></i> Ver
                            </a>

                            <a href="{{ route('clientes.confirmBaja', $cliente) }}" class="btn btn-sm btn-secondary me-1">
                                <i class="fas fa-user-minus"></i> Dar de baja
                            </a>

                            @else
                            <a href="{{ route('clientes.confirmAlta', $cliente) }}" class="btn btn-sm btn-success">
                                <i class="fas fa-user-check"></i> Reactivar
                            </a>
                            @endif
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-3">
                            No hay clientes registrados.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection