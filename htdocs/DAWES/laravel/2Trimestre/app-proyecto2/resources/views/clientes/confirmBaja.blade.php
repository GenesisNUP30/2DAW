@extends('layouts.app')

@section('titulo', 'Confirmar baja de cliente')

@section('content')
<div class="container py-3">

    {{-- Cabecera --}}
    <div class="mb-4">
        <h2 class="fw-bold m-0 text-dark">
            <i class="fas fa-user-minus me-2"></i>Dar de baja cliente
        </h2>
    </div>

    {{-- Aviso de Acción (Estilo Ámbar/Precaución) --}}
    <div class="card border mb-3" style="border-color: #fde68a !important; border-radius: 12px; background: #fffbeb;">
        <div class="card-body d-flex align-items-start gap-3 py-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                 style="width:40px;height:40px;background:#fef3c7;">
                <i class="fas fa-exclamation-triangle text-warning"></i>
            </div>
            <div>
                <p class="fw-semibold text-warning mb-1" style="color: #92400e !important;">¿Confirmar cese de actividad?</p>
                <p class="text-muted mb-0">
                    Estás a punto de dar de baja a <strong>{{ $cliente->nombre }}</strong>. 
                    El cliente ya no aparecerá en los listados activos, pero <strong>se mantendrá todo su historial</strong> de tareas y cuotas en el sistema.
                </p>
            </div>
        </div>
    </div>

    {{-- Resumen del Cliente --}}
    <div class="card border shadow-sm mb-4" style="border-color: #e5e7eb !important; border-radius: 12px;">
        <div class="card-header bg-light border-bottom py-2 px-3"
             style="font-size:.7rem; font-weight:600; text-transform:uppercase; letter-spacing:.06em; color:#6b7280;">
            Ficha técnica del cliente
        </div>
        <div class="card-body px-3 py-2">

            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted small">Nombre / Razón Social</span>
                <span class="fw-bold text-dark">{{ $cliente->nombre }}</span>
            </div>

            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted small">CIF</span>
                <span class="text-dark">{{ $cliente->cif ?? '—' }}</span>
            </div>

            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted small">Contacto</span>
                <div class="text-end">
                    <div class="text-dark">{{ $cliente->telefono ?? '—' }}</div>
                    <div class="text-muted small">{{ $cliente->correo }}</div>
                </div>
            </div>

            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted small">País y Moneda</span>
                <span class="text-dark">
                    {{ $cliente->paisRelacion ? $cliente->paisRelacion->nombre : $cliente->pais }} 
                    ({{ $cliente->moneda }})
                </span>
            </div>

            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted small">Cuota Mensual</span>
                <span class="fw-bold text-dark">{{ number_format($cliente->importe_cuota_mensual, 2, ',', '.') }} {{ $cliente->moneda }}</span>
            </div>

            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted small">Cuenta Corriente</span>
                <span class="text-dark">{{ $cliente->cuenta_corriente }}</span>
            </div>

            <div class="d-flex justify-content-between py-2">
                <span class="text-muted small">Fecha de alta inicial</span>
                <span class="text-dark">{{ optional($cliente->fecha_alta)->format('d/m/Y') ?? '—' }}</span>
            </div>

        </div>
    </div>

    {{-- Botones de Acción --}}
    <form action="{{ route('clientes.baja', $cliente) }}" method="POST">
        @csrf
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-warning px-4 fw-bold shadow-sm" style="color: #451a03;">
                <i class="fas fa-user-minus me-2"></i>Sí, dar de baja
            </button>
            <a href="{{ route('clientes.index') }}" class="btn btn-light border px-4 text-muted">
                <i class="fas fa-times me-1"></i> Cancelar
            </a>
        </div>
    </form>

</div>
@endsection