@extends('layouts.app')

@section('titulo', 'Lista de clientes')

@section('content')
<div class="container">
    <h1 class="mb-4">
        <i class="fas fa-users"></i> Lista de clientes
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
                            <th>Cuenta corriente</th>
                            <th>Pais</th>
                            <th>Moneda</th>
                            <th>Importe cuota mensual</th>
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
                            <td>{{ $cliente->cuenta_corriente }}</td>
                            <td>{{ $cliente->paisRelacion ? $cliente->paisRelacion->nombre : '—' }}</td>
                            <td>{{ $cliente->moneda }}</td>
                            <td>{{ number_format($cliente->importe_cuota_mensual, 2) }} €</td>
                            <td>{{ optional($cliente->fecha_alta)->format('d/m/Y') }}</td>

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