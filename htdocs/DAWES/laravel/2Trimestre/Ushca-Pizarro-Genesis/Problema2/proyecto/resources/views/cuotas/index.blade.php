@extends('layouts.app')

@section('titulo', 'Listado de cuotas')

@section('content')
<div class="container-fluid px-4 py-3">

    {{-- Cabecera --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold m-0 text-dark">
                <i class="fas fa-file-invoice-dollar me-2"></i>Listado de Cuotas
            </h2>
            <p class="text-muted mb-0">Gestión de facturación mensual y cargos excepcionales.</p>
        </div>

        @if (auth()->user()->isAdmin())
        <div class="d-flex gap-2">
            <a href="{{ route('cuotas.generarRemesa') }}" class="btn btn-primary btn-sm px-3 shadow-sm">
                <i class="fas fa-magic me-1"></i> Generar Remesa
            </a>
            <a href="{{ route('cuotas.create') }}" class="btn btn-dark btn-sm px-3 shadow-sm">
                <i class="fas fa-plus me-1"></i> Cuota Excepcional
            </a>
            <a href="{{ route('cuotas.papelera') }}" class="btn btn-outline-danger btn-sm px-3 shadow-sm">
                <i class="fas fa-trash-alt me-1"></i> Papelera
            </a>
        </div>
        @endif
    </div>

    {{-- Mensajes de éxito --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Tabs de Navegación --}}
    <div class="mb-3">
        <ul class="nav nav-pills gap-2 p-1 bg-white border d-inline-flex rounded-pill shadow-sm" id="cuotasTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active rounded-pill border-0 px-4 fw-semibold" data-bs-toggle="tab" data-bs-target="#mensuales" type="button">
                    <i class="fas fa-calendar-alt me-2"></i>MENSUALES
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link rounded-pill border-0 px-4 fw-semibold" data-bs-toggle="tab" data-bs-target="#excepcionales" type="button">
                    <i class="fas fa-star me-2"></i>EXCEPCIONALES
                </button>
            </li>
        </ul>
    </div>

    <div class="tab-content" id="cuotasTabsContent">
        
        {{-- TAB: CUOTAS MENSUALES --}}
        <div class="tab-pane fade show active" id="mensuales" role="tabpanel">
            <div class="card border shadow-sm" style="border-color: #e5e7eb !important; border-radius: 12px; overflow: hidden;">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background: #f9fafb;">
                        <tr class="text-uppercase fw-bold text-muted" style="font-size: 0.75rem; letter-spacing: .05em;">
                            <th class="ps-4 py-3 border-0">Cliente</th>
                            <th class="py-3 border-0">Concepto</th>
                            <th class="py-3 border-0">Importe</th>
                            <th class="py-3 border-0">Emisión</th>
                            <th class="py-3 border-0">Estado</th>
                            <th class="py-3 pe-4 border-0 text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($cuotasMensuales as $cuota)
                        <tr style="border-top: 1px solid #f3f4f6;">
                            <td class="ps-4">
                                <div class="fw-bold text-dark">{{ $cuota->cliente->nombre }}</div>
                                <div class="text-muted" style="font-size: 12px;">{{ $cuota->cliente->cif }}</div>
                            </td>
                            <td><span class="text-secondary">{{ $cuota->concepto }}</span></td>
                            <td><span class="fw-bold text-dark">{{ number_format($cuota->importe, 2, ',', '.') }}€</span></td>
                            <td class="text-muted">{{ $cuota->fecha_emision->format('d/m/Y') }}</td>
                            <td>
                                @if ($cuota->isPagada())
                                    <span class="badge rounded-pill border bg-success bg-opacity-10 text-success px-3 py-2">
                                        Pagada ({{ $cuota->fecha_pago->format('d/m/Y') }})
                                    </span>
                                @else
                                    <span class="badge rounded-pill border bg-warning bg-opacity-10 text-warning px-3 py-2">
                                        Pendiente
                                    </span>
                                @endif
                            </td>
                            <td class="pe-4 text-end">
                                <div class="d-flex gap-1 justify-content-end">
                                    <a href="{{ route('cuotas.edit', $cuota) }}" class="btn btn-sm btn-light border" style="width:34px;height:34px;display:flex;align-items:center;justify-content:center;" title="Editar">
                                        <i class="fas fa-pen text-warning"></i>
                                    </a>
                                    <a href="{{ route('facturas.confirmar', $cuota->id) }}" class="btn btn-sm btn-light border" style="width:34px;height:34px;display:flex;align-items:center;justify-content:center;" title="Factura">
                                        <i class="fas fa-file-invoice text-primary"></i>
                                    </a>
                                    <a href="{{ route('cuotas.confirmDelete', $cuota) }}" class="btn btn-sm btn-light border" style="width:34px;height:34px;display:flex;align-items:center;justify-content:center;" title="Eliminar">
                                        <i class="fas fa-trash text-danger"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted fst-italic">
                                <i class="fas fa-receipt d-block mb-2 opacity-25 fa-3x"></i>
                                No hay cuotas mensuales registradas.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $cuotasMensuales->links() }}
            </div>
        </div>

        {{-- TAB: CUOTAS EXCEPCIONALES --}}
        <div class="tab-pane fade" id="excepcionales" role="tabpanel">
            <div class="card border shadow-sm" style="border-color: #e5e7eb !important; border-radius: 12px; overflow: hidden;">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background: #f9fafb;">
                        <tr class="text-uppercase fw-bold text-muted" style="font-size: 0.75rem; letter-spacing: .05em;">
                            <th class="ps-4 py-3 border-0">Cliente</th>
                            <th class="py-3 border-0">Concepto</th>
                            <th class="py-3 border-0">Importe</th>
                            <th class="py-3 border-0">Emisión</th>
                            <th class="py-3 border-0">Estado</th>
                            <th class="py-3 pe-4 border-0 text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($cuotasExcepcionales as $cuota)
                        <tr style="border-top: 1px solid #f3f4f6;">
                            <td class="ps-4">
                                <div class="fw-bold text-dark">{{ $cuota->cliente->nombre }}</div>
                                <div class="text-muted" style="font-size: 12px;">{{ $cuota->cliente->cif }}</div>
                            </td>
                            <td><span class="text-secondary">{{ $cuota->concepto }}</span></td>
                            <td><span class="fw-bold text-dark">{{ number_format($cuota->importe, 2, ',', '.') }}€</span></td>
                            <td class="text-muted">{{ $cuota->fecha_emision->format('d/m/Y') }}</td>
                            <td>
                                @if ($cuota->isPagada())
                                    <span class="badge rounded-pill border bg-success bg-opacity-10 text-success px-3 py-2">Pagada</span>
                                @else
                                    <span class="badge rounded-pill border bg-warning bg-opacity-10 text-warning px-3 py-2">Pendiente</span>
                                @endif
                            </td>
                            <td class="pe-4 text-end">
                                <div class="d-flex gap-1 justify-content-end">
                                    <a href="{{ route('cuotas.edit', $cuota) }}" class="btn btn-sm btn-light border" style="width:34px;height:34px;display:flex;align-items:center;justify-content:center;">
                                        <i class="fas fa-pen text-warning"></i>
                                    </a>
                                    <a href="{{ route('cuotas.confirmDelete', $cuota) }}" class="btn btn-sm btn-light border" style="width:34px;height:34px;display:flex;align-items:center;justify-content:center;">
                                        <i class="fas fa-trash text-danger"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted fst-italic">
                                <i class="fas fa-star d-block mb-2 opacity-25 fa-3x"></i>
                                No hay cuotas excepcionales registradas.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $cuotasExcepcionales->links() }}
            </div>
        </div>
    </div>
</div>
@endsection