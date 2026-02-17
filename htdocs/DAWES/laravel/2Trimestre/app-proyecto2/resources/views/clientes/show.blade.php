@extends('layouts.app')

@section('titulo', 'Detalles del cliente')

@section('content')
<div class="container">
    <h1 class="mb-4">
        <i class="fas fa-user-tie"></i> Detalles del cliente: {{ $cliente->nombre }}
    </h1>

    {{-- MENSAJE DE ÉXITO/ERROR --}}
    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i> Información completa</h5>
            <span class="badge bg-{{ $cliente->isBaja() ? 'secondary' : 'success' }}">
                {{ $cliente->isBaja() ? 'De baja' : 'Activo' }}
            </span>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold"><i class="far fa-id-card me-1"></i> CIF</label>
                        <p class="form-control-plaintext">{{ $cliente->cif }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold"><i class="fas fa-user me-1"></i> Nombre</label>
                        <p class="form-control-plaintext">{{ $cliente->nombre }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold"><i class="fas fa-phone me-1"></i> Teléfono</label>
                        <p class="form-control-plaintext">{{ $cliente->telefono }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold"><i class="fas fa-envelope me-1"></i> Correo</label>
                        <p class="form-control-plaintext">{{ $cliente->correo }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold"><i class="fas fa-credit-card me-1"></i> Cuenta corriente</label>
                        <p class="form-control-plaintext">{{ $cliente->cuenta_corriente }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold"><i class="fas fa-globe me-1"></i> País</label>
                        <p class="form-control-plaintext">
                            {{ $cliente->paisRelacion ? $cliente->paisRelacion->nombre : $cliente->pais }}
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold"><i class="fas fa-money-bill-wave me-1"></i> Moneda</label>
                        <p class="form-control-plaintext">{{ $cliente->moneda }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold"><i class="fas fa-euro-sign me-1"></i> Importe cuota</label>
                        <p class="form-control-plaintext">{{ number_format($cliente->importe_cuota_mensual, 2, ',', '.') }} €</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold"><i class="fas fa-calendar-plus me-1"></i> Fecha de alta</label>
                        <p class="form-control-plaintext">
                            {{ \Carbon\Carbon::parse($cliente->fecha_alta)->format('d/m/Y') }}
                        </p>
                    </div>
                    @if($cliente->isBaja())
                    <div class="mb-3">
                        <label class="form-label fw-bold text-danger"><i class="fas fa-calendar-times me-1"></i> Fecha de baja</label>
                        <p class="form-control-plaintext text-danger">
                            {{ $cliente->fecha_baja ? \Carbon\Carbon::parse($cliente->fecha_baja)->format('d/m/Y') : '-' }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>

            <hr class="my-4">

            {{-- BOTONES DE ACCIÓN --}}
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Volver a la lista
                </a>
            </div>
        </div>
    </div>

    {{-- SECCIÓN DE TAREAS RELACIONADAS (opcional pero útil) --}}
    @if($cliente->tareas()->count() > 0 || $cliente->cuotas()->count() > 0)
    <div class="card mt-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="fas fa-link me-2"></i> Relaciones</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6><i class="fas fa-tasks me-1"></i> Tareas asignadas: {{ $cliente->tareas()->count() }}</h6>
                    @if($cliente->tareas()->count() > 0)
                    <ul class="list-group">
                        @foreach($cliente->tareas()->limit(5)->get() as $tarea)
                        <li class="list-group-item">
                            <span class="badge bg-{{ $tarea->estado === 'R' ? 'success' : ($tarea->estado === 'C' ? 'danger' : 'warning') }}">
                                {{ $tarea->estado }}
                            </span>
                            {{ \Illuminate\Support\Str::limit($tarea->descripcion, 50) }}
                            <small class="text-muted d-block mt-1">
                                <i class="fas fa-calendar me-1"></i> {{ $tarea->fecha_realizacion ? $tarea->fecha_realizacion->format('d/m/Y') : '-' }}
                            </small>
                        </li>
                        @endforeach
                        @if($cliente->tareas()->count() > 5)
                        <li class="list-group-item text-center">
                            <small class="text-muted">y {{ $cliente->tareas()->count() - 5 }} más...</small>
                        </li>
                        @endif
                    </ul>
                    @else
                    <p class="text-muted">No hay tareas asignadas.</p>
                    @endif
                </div>
                <div class="col-md-6">
                    <h6><i class="fas fa-file-invoice-dollar me-1"></i> Cuotas registradas: {{ $cliente->cuotas()->count() }}</h6>
                    @if($cliente->cuotas()->count() > 0)
                    <ul class="list-group">
                        @foreach($cliente->cuotas()->limit(5)->get() as $cuota)
                        <li class="list-group-item">
                            <span class="badge bg-{{ $cuota->pagada ? 'success' : 'warning' }}">
                                {{ $cuota->pagada ? 'Pagada' : 'Pendiente' }}
                            </span>
                            {{ $cuota->concepto }} - {{ number_format($cuota->importe, 2, ',', '.') }} €
                            <small class="text-muted d-block mt-1">
                                <i class="fas fa-calendar me-1"></i> {{ $cuota->fecha_emision ? \Carbon\Carbon::parse($cuota->fecha_emision)->format('d/m/Y') : '-' }}
                            </small>
                        </li>
                        @endforeach
                        @if($cliente->cuotas()->count() > 5)
                        <li class="list-group-item text-center">
                            <small class="text-muted">y {{ $cliente->cuotas()->count() - 5 }} más...</small>
                        </li>
                        @endif
                    </ul>
                    @else
                    <p class="text-muted">No hay cuotas registradas.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection