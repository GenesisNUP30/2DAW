@extends('layouts.app')

@section('titulo', 'Listado de Cuotas')

@section('content')
<div class="container-fluid px-4 py-3">

    {{-- Cabecera y Acciones Principales --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold m-0 text-dark">
                <i class="fas fa-file-invoice-dollar me-2"></i>Listado de Cuotas
            </h2>
        </div>

        @if (auth()->user()->isAdmin())
        <div class="d-flex gap-2">
            <a href="{{ route('cuotas.generarRemesa') }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-magic me-2"></i>Generar Remesa
            </a>
            <a href="{{ route('cuotas.create') }}" class="btn btn-dark shadow-sm">
                <i class="fas fa-plus-circle me-2"></i>Cuota Excepcional
            </a>
            <a href="{{ route('cuotas.papelera') }}" class="btn btn-outline-danger border shadow-sm">
                <i class="fas fa-trash-alt me-2"></i>Papelera
            </a>
        </div>
        @endif
    </div>

    {{-- Alertas de Sistema --}}
    @if (session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Tabs de Navegación --}}
    <ul class="nav nav-pills mb-3 bg-light p-1 rounded-pill d-inline-flex border" id="cuotasTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active rounded-pill px-4 py-2" data-bs-toggle="tab" data-bs-target="#mensuales" type="button">
                <i class="fas fa-calendar-alt me-2"></i>Mensuales
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill px-4 py-2" data-bs-toggle="tab" data-bs-target="#excepcionales" type="button">
                <i class="fas fa-star me-2"></i>Excepcionales
            </button>
        </li>
    </ul>

    <div class="tab-content" id="cuotasTabsContent">
        
        {{-- TAB: CUOTAS MENSUALES --}}
        <div class="tab-pane fade show active" id="mensuales" role="tabpanel">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 text-muted small fw-bold text-uppercase">Cliente</th>
                                <th class="text-muted small fw-bold text-uppercase">Concepto</th>
                                <th class="text-muted small fw-bold text-uppercase">Importe</th>
                                <th class="text-muted small fw-bold text-uppercase">Emisión</th>
                                <th class="text-muted small fw-bold text-uppercase">Estado</th>
                                <th class="text-end pe-4 text-muted small fw-bold text-uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($cuotasMensuales as $cuota)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark">{{ $cuota->cliente->nombre }}</div>
                                    <div class="text-muted small">{{ $cuota->cliente->cif }}</div>
                                </td>
                                <td><span class="text-muted">{{ $cuota->concepto }}</span></td>
                                <td><span class="fw-bold text-dark">{{ number_format($cuota->importe, 2, ',', '.') }} €</span></td>
                                <td>{{ $cuota->fecha_emision->format('d/m/Y') }}</td>
                                <td>
                                    @if ($cuota->isPagada())
                                        <span class="badge rounded-pill bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3">
                                            Pagada el {{ $cuota->fecha_pago->format('d/m/Y') }}
                                        </span>
                                    @else
                                        <span class="badge rounded-pill bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-3">
                                            Pendiente
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group">
                                        <a href="{{ route('cuotas.edit', $cuota) }}" class="btn btn-sm btn-light border text-muted" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('facturas.confirmar', $cuota->id) }}" class="btn btn-sm btn-light border text-primary" title="Generar Factura">
                                            <i class="fas fa-file-invoice"></i>
                                        </a>
                                        <a href="{{ route('cuotas.confirmDelete', $cuota) }}" class="btn btn-sm btn-light border text-danger" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-receipt fa-3x mb-3 opacity-25"></i>
                                    <p>No se han encontrado cuotas mensuales registradas.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-4 d-flex justify-content-center">
                {{ $cuotasMensuales->links() }}
            </div>
        </div>

        {{-- TAB: CUOTAS EXCEPCIONALES --}}
        <div class="tab-pane fade" id="excepcionales" role="tabpanel">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 text-muted small fw-bold text-uppercase">Cliente</th>
                                <th class="text-muted small fw-bold text-uppercase">Concepto</th>
                                <th class="text-muted small fw-bold text-uppercase">Importe</th>
                                <th class="text-muted small fw-bold text-uppercase">Emisión</th>
                                <th class="text-muted small fw-bold text-uppercase">Estado</th>
                                <th class="text-end pe-4 text-muted small fw-bold text-uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($cuotasExcepcionales as $cuota)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark">{{ $cuota->cliente->nombre }}</div>
                                    <div class="text-muted small">{{ $cuota->cliente->cif }}</div>
                                </td>
                                <td><span class="text-muted">{{ $cuota->concepto }}</span></td>
                                <td><span class="fw-bold text-dark">{{ number_format($cuota->importe, 2, ',', '.') }} €</span></td>
                                <td>{{ $cuota->fecha_emision->format('d/m/Y') }}</td>
                                <td>
                                    @if ($cuota->isPagada())
                                        <span class="badge rounded-pill bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3">Pagada</span>
                                    @else
                                        <span class="badge rounded-pill bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-3">Pendiente</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group">
                                        <a href="{{ route('cuotas.edit', $cuota) }}" class="btn btn-sm btn-light border text-muted" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('cuotas.confirmDelete', $cuota) }}" class="btn btn-sm btn-light border text-danger" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-star fa-3x mb-3 opacity-25"></i>
                                    <p>No hay cuotas excepcionales pendientes.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-4 d-flex justify-content-center">
                {{ $cuotasExcepcionales->links() }}
            </div>
        </div>
    </div>
</div>
@endsection