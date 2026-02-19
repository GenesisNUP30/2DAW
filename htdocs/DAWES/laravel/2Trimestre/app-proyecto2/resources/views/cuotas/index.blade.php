@extends('layouts.app')

@section('titulo', 'Lista de cuotas')

@section('content')
<div class="container">
    <h1 class="mb-4">
        <i class="fas fa-file-invoice-dollar"></i> Lista de cuotas
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
        {{-- Botón para generar remesa mensual (solo administrador) --}}
        @if (auth()->user()->isAdmin())
        <a href="{{ route('cuotas.generarRemesa') }}" class="btn btn-primary mb-3">
            <i class="fas fa-file-invoice me-2"></i> Generar Remesa Mensual
        </a>
        <a href="{{ route('cuotas.create') }}" class="btn btn-success mb-3">
            <i class="fas fa-file-invoice me-2"></i> Añadir cuota excepcional
        </a>
        @endif
    </div>

    {{-- Tabla de cuotas --}}
    <div class="card">
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Importe</th>
                        <th>Fecha de emisión</th>
                        <th>Pagada</th>
                        <th>Fecha de pago</th>
                        <th>Notas</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($cuotas as $cuota)
                    <tr>
                        <td>{{ $cuota->id }}</td>
                        <td>{{ $cuota->cliente->nombre }}</td>
                        <td>{{ number_format($cuota->importe, 2, ',', '.') }} €</td>
                        <td>{{ \Carbon\Carbon::parse($cuota->fecha_emision)->format('d/m/Y') }}</td>
                        <td>
                            @if ($cuota->isPagada())
                            <span class="badge bg-success">Pagada</span>
                            @else
                            <span class="badge bg-secondary">Pendiente</span>
                            @endif
                        </td>
                        <td>
                            @if ($cuota->isPagada())
                            {{ \Carbon\Carbon::parse($cuota->fecha_pago)->format('d/m/Y') }}
                            @endif
                        </td>
                        <td>{{ $cuota->notas }}</td>
                        <td class="text-end">
                            <a href="{{ route('cuotas.edit', $cuota) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i> Editar
                            </a>

                            <a href="{{ route('cuotas.confirmDelete', $cuota) }}" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i> Eliminar
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-3">
                            No hay cuotas registradas.
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection