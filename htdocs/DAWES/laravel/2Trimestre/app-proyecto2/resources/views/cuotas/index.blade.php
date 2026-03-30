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
    <div class=" mb-3">
        {{-- Botón para generar remesa mensual (solo administrador) --}}
        @if (auth()->user()->isAdmin())
        <a href="{{ route('cuotas.generarRemesa') }}" class="btn btn-black">
            <i class="fa-solid fa-circle-plus"></i> Generar Remesa Mensual
        </a>
        <a href="{{ route('cuotas.create') }}" class="btn btn-black">
            <i class="fa-solid fa-circle-plus"></i> Añadir cuota excepcional
        </a>

        {{-- Botón Papelera --}}
        <a href="{{ route('cuotas.papelera') }}" class="btn btn-outline-danger">
            <i class="fas fa-trash"></i> Ver Papelera
        </a>
        @endif
    </div>

    {{-- Tabs --}}
    <ul class="nav nav-tabs mb-3" id="cuotasTabs">
        <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#mensuales">
                Cuotas mensuales
            </button>
        </li>

        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#excepcionales">
                Cuotas excepcionales
            </button>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade show active" id="mensuales">
            <div class="card">
                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Cliente</th>
                                <th>Concepto</th>
                                <th>Importe</th>
                                <th>Fecha emisión</th>
                                <th>Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($cuotasMensuales as $cuota)
                            <tr>
                                <td>{{ $cuota->cliente->nombre }}</td>
                                <td>{{ $cuota->concepto }}</td>
                                <td>{{ number_format($cuota->importe,2,',','.') }} €</td>

                                <td>{{ $cuota->fecha_emision->format('d/m/Y') }}</td>

                                <td>
                                    @if ($cuota->isPagada())
                                    <span class="badge bg-success">Pagada</span>
                                    @else
                                    <span class="badge bg-secondary">Pendiente</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('cuotas.edit', $cuota) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>

                                    <a href="{{ route('cuotas.confirmDelete', $cuota) }}" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </a>
                                    <a href="{{ route('facturas.confirmar', $cuota->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fa-solid fa-file-invoice"></i> Factura
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-3">
                                    No hay cuotas mensuales.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-3">
                {{ $cuotasMensuales->links() }}
            </div>
        </div>
        <div class="tab-pane fade" id="excepcionales">
            <div class="class">
                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Cliente</th>
                                <th>Concepto</th>
                                <th>Importe</th>
                                <th>Fecha emisión</th>
                                <th>Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($cuotasExcepcionales as $cuota)
                            <tr>
                                <td>{{ $cuota->cliente->nombre }}</td>
                                <td>{{ $cuota->concepto }}</td>
                                <td>{{ number_format($cuota->importe,2,',','.') }} €</td>

                                <td>{{ $cuota->fecha_emision->format('d/m/Y') }}</td>

                                <td>
                                    @if ($cuota->isPagada())
                                    <span class="badge bg-success">Pagada</span>
                                    @else
                                    <span class="badge bg-secondary">Pendiente</span>
                                    @endif
                                </td>
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
                                <td colspan="7" class="text-center py-3">
                                    No hay cuotas mensuales.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-3">
                {{ $cuotasExcepcionales->links() }}
            </div>
        </div>
    </div>
    @endsection