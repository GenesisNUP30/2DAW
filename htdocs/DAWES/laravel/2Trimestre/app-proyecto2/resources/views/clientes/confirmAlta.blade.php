@extends('layouts.app')

@section('titulo', 'Confirmar reactivación de cliente')

@section('content')
<div class="container py-3">

    {{-- Cabecera --}}
    <div class="mb-4">
        <h2 class="fw-bold m-0 text-dark">
            <i class="fas fa-user-check me-2"></i>Reactivar cliente
        </h2>
    </div>

    {{-- Aviso de Acción (Estilo Verde/Activación) --}}
    <div class="card border mb-3" style="border-color: #a7f3d0 !important; border-radius: 12px; background: #f0fdf4;">
        <div class="card-body d-flex align-items-start gap-3 py-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                 style="width:40px;height:40px;background:#d1fae5;">
                <i class="fas fa-check text-success"></i>
            </div>
            <div>
                <p class="fw-semibold text-success mb-1" style="color: #065f46 !important;">Confirmar reactivación de cliente</p>
                <p class="text-muted mb-0">
                    Estás a punto de reactivar a <strong>{{ $cliente->nombre }}</strong>. 
                    El cliente volverá a aparecer en los listados operativos y podrá recibir <strong>nuevas tareas y facturación</strong> de cuotas.
                </p>
            </div>
        </div>
    </div>

    {{-- Resumen del Cliente --}}
    <div class="card border shadow-sm mb-4" style="border-color: #e5e7eb !important; border-radius: 12px;">
        <div class="card-header bg-light border-bottom py-2 px-3"
             style="font-size:.7rem; font-weight:600; text-transform:uppercase; letter-spacing:.06em; color:#6b7280;">
            Información del registro
        </div>
        <div class="card-body px-3 py-2">

            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">Nombre / Razón Social</span>
                <span class="fw-bold text-dark">{{ $cliente->nombre }}</span>
            </div>

            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">CIF</span>
                <span class="text-dark">{{ $cliente->cif ?? '—' }}</span>
            </div>

            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">Contacto principal</span>
                <div class="text-end">
                    <div class="text-dark">{{ $cliente->telefono ?? '—' }}</div>
                    <div class="text-muted">{{ $cliente->correo }}</div>
                </div>
            </div>

            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">País y Moneda</span>
                <span class="text-dark">
                    {{ $cliente->paisRelacion ? $cliente->paisRelacion->nombre : $cliente->pais }} 
                    ({{ $cliente->moneda }})
                </span>
            </div>

            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">Cuota Mensual</span>
                <span class="fw-bold text-dark">{{ number_format($cliente->importe_cuota_mensual, 2, ',', '.') }} {{ $cliente->moneda }}</span>
            </div>

            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">Fecha de alta original</span>
                <span class="text-dark">{{ optional($cliente->fecha_alta)->format('d/m/Y') ?? '—' }}</span>
            </div>

            <div class="d-flex justify-content-between py-2 text-danger">
                <span class="fw-bold">Inactivo desde el</span>
                <span class="fw-bold">{{ optional($cliente->fecha_baja)->format('d/m/Y') ?? '—' }}</span>
            </div>

        </div>
    </div>

    {{-- Botones de Acción --}}
    <form action="{{ route('clientes.alta', $cliente) }}" method="POST">
        @csrf
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success px-4 fw-bold shadow-sm">
                <i class="fas fa-user-check me-2"></i>Sí, reactivar cliente
            </button>
            <a href="{{ route('clientes.index') }}" class="btn btn-light border px-4 text-muted">
                <i class="fas fa-times me-1"></i> Cancelar y volver
            </a>
        </div>
    </form>

</div>
@endsection