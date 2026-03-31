@extends('layouts.app')

@section('titulo', 'Detalle: ' . $cliente->nombre)

@section('content')
<div class="container-fluid px-4 py-3">

    {{-- Header de Perfil de Cliente --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px; background: linear-gradient(to right, #ffffff, #f9fafb);">
        <div class="card-body p-4">
            <div class="d-flex align-items-center gap-4">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <h2 class="fw-bold m-0 text-dark">{{ $cliente->nombre }}</h2>
                        @if($cliente->isActivo())
                        <span class="badge rounded-pill bg-success bg-opacity-10 text-success border border-success border-opacity-25 small px-3">Activo</span>
                        @else
                        <span class="badge rounded-pill bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 small px-3">Dado de baja</span>
                        @endif
                    </div>
                    <p class="text-muted mb-0">
                        <i class="far fa-id-card me-1"></i> CIF: <span class="fw-semibold">{{ $cliente->cif }}</span>
                        <span class="mx-2 text-light">|</span>
                        <i class="fas fa-map-marker-alt me-1"></i> {{ $cliente->paisRelacion->nombre ?? $cliente->pais }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Columna Izquierda: Datos de Contacto y Financieros --}}
        <div class="col-lg-8">
            <div class="card border shadow-sm mb-4" style="border-radius: 12px;">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h5 class="fw-bold m-0" style="font-size: 1rem;">Información General</h5>
                </div>
                <div class="card-body pt-0">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="p-3 rounded border bg-light bg-opacity-50">
                                <label class="text-muted small fw-bold text-uppercase d-block mb-1">Contacto Directo</label>
                                <div class="mb-2"><i class="fas fa-phone text-muted me-2"></i>{{ $cliente->telefono }}</div>
                                <div><i class="fas fa-envelope text-muted me-2"></i>{{ $cliente->correo }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded border bg-light bg-opacity-50">
                                <label class="text-muted small fw-bold text-uppercase d-block mb-1">Datos de Facturación</label>
                                <div class="mb-2"><i class="fas fa-credit-card text-muted me-2"></i>{{ $cliente->cuenta_corriente }}</div>
                                <div><i class="fas fa-coins text-muted me-2"></i>{{ $cliente->moneda }} - {{ number_format($cliente->importe_cuota_mensual, 2, ',', '.') }} {{ $cliente->moneda }} / mes</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small fw-bold text-uppercase d-block mb-1">Registro en sistema</label>
                            <span class="text-dark"><i class="far fa-calendar-alt me-2"></i>Alta: {{ optional($cliente->fecha_alta)->format('d/m/Y') ?? '—' }}</span>
                        </div>
                        @if($cliente->isBaja())
                        <div class="col-md-6">
                            <label class="text-muted small fw-bold text-uppercase d-block mb-1 text-danger">Fecha de Cese</label>
                            <span class="text-danger fw-bold"><i class="fas fa-calendar-times me-2"></i>{{ optional($cliente->fecha_baja)->format('d/m/Y') }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Relaciones (Tareas y Cuotas) --}}
            <div class="card border shadow-sm" style="border-radius: 12px;">
                <div class="card-header bg-white py-3 border-bottom-0 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold m-0" style="font-size: 1rem;">Actividad Reciente</h5>
                </div>
                <div class="card-body pt-0">
                    <ul class="nav nav-pills mb-3 gap-2" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active small py-1 px-3 border" id="pills-tareas-tab" data-bs-toggle="pill" data-bs-target="#pills-tareas" type="button" role="tab">Tareas ({{ $cliente->tareas->count() }})</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link small py-1 px-3 border" id="pills-cuotas-tab" data-bs-toggle="pill" data-bs-target="#pills-cuotas" type="button" role="tab">Cuotas ({{ $cliente->cuotas->count() }})</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        {{-- Tab Tareas --}}
                        <div class="tab-pane fade show active" id="pills-tareas" role="tabpanel">
                            @forelse($cliente->tareas()->limit(5)->get() as $tarea)
                            <div class="d-flex justify-content-between align-items-center p-2 border-bottom">
                                <span class="small text-dark">{{ Str::limit($tarea->descripcion, 60) }}</span>
                                <span class="badge bg-light text-muted border small" style="font-size: 0.65rem;">{{ $tarea->estado }}</span>
                            </div>
                            @empty
                            <p class="text-muted small my-3 text-center">Sin tareas registradas.</p>
                            @endforelse
                        </div>
                        {{-- Tab Cuotas --}}
                        <div class="tab-pane fade" id="pills-cuotas" role="tabpanel">
                            @forelse($cliente->cuotas()->limit(5)->get() as $cuota)
                            <div class="d-flex justify-content-between align-items-center p-2 border-bottom">
                                <span class="small text-dark">{{ $cuota->concepto ?? 'Mensualidad' }}</span>
                                <span class="fw-bold small text-dark">{{ number_format($cuota->importe, 2, ',', '.') }}€</span>
                            </div>
                            @empty
                            <p class="text-muted small my-3 text-center">Sin cuotas registradas.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Columna Derecha: Acciones Rápidas --}}
        <div class="col-lg-4">
            <div class="card border shadow-sm sticky-top" style="border-radius: 12px; top: 20px;">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-uppercase mb-3" style="font-size: 0.75rem; letter-spacing: 0.05em; color: #6b7280;">Gestión Administrativa</h6>

                    <div class="d-grid gap-2">
                        {{-- Aquí podrías poner el botón de Editar cuando lo tengas listo --}}
                        {{-- <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-dark text-white"><i class="fas fa-pen me-2"></i> Editar Cliente</a> --}}

                        <a href="{{ route('clientes.index') }}" class="btn btn-light border px-3">
                            <i class="fas fa-arrow-left me-1"></i> Volver al listado
                        </a>
                        
                        @if($cliente->isActivo())
                        <a href="{{ route('clientes.confirmBaja', $cliente) }}" class="btn btn-outline-warning">
                            <i class="fas fa-user-minus me-2"></i> Dar de baja
                        </a>
                        @else
                        <a href="{{ route('clientes.confirmAlta', $cliente) }}" class="btn btn-outline-success">
                            <i class="fas fa-user-check me-2"></i> Reactivar Cliente
                        </a>
                        @endif

                        <hr class="my-2 opacity-50">

                        <button class="btn btn-light border text-muted small py-2" onclick="window.print()">
                            <i class="fas fa-print me-2"></i> Imprimir Ficha
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection