@extends('layouts.app')

@section('titulo', 'Confirmar baja de empleado')

@section('content')
<div class="container py-3">

    {{-- Cabecera --}}
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h2 class="fw-bold m-0 text-dark">Dar de baja empleado</h2>
        </div>
        <a href="{{ route('empleados.index') }}" class="btn btn-sm btn-light border">
            <i class="fas fa-arrow-left me-1"></i> Volver al listado
        </a>
    </div>

    {{-- Aviso de Acción de Suspensión (Estilo Ámbar) --}}
    <div class="card border mb-3" style="border-color: #fde68a !important; border-radius: 12px; background: #fffbeb;">
        <div class="card-body d-flex align-items-start gap-3 py-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                 style="width:40px;height:40px;background:#fef3c7;">
                <i class="fas fa-user-minus text-warning"></i>
            </div>
            <div>
                <p class="fw-semibold text-warning mb-1" style="color: #92400e !important;">Confirmar cese de actividad</p>
                <p class="text-muted mb-0">
                    Estás a punto de dar de baja a <strong>{{ $empleado->name }}</strong>. 
                    El empleado ya no aparecerá en las listas activas ni podrá asignársele nuevas tareas, 
                    pero <strong>su historial y datos se conservarán</strong> en el sistema.
                </p>
            </div>
        </div>
    </div>

    {{-- Resumen del empleado --}}
    <div class="card border shadow-sm mb-4" style="border-color: #e5e7eb !important; border-radius: 12px;">
        <div class="card-header bg-light border-bottom py-2 px-3"
             style="font-size:.7rem; font-weight:600; text-transform:uppercase; letter-spacing:.06em; color:#6b7280;">
            Ficha del empleado a desactivar
        </div>
        <div class="card-body px-3 py-2">

            {{-- Avatar e Iniciales --}}
            <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                <span class="text-muted">Empleado</span>
                <div class="d-flex align-items-center gap-2">
                    @php
                        $iniciales = collect(explode(' ', $empleado->name))
                            ->take(2)->map(fn($p) => strtoupper($p[0]))->implode('');
                    @endphp
                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-semibold"
                         style="width:32px;height:32px;background:#fef3c7;color:#92400e;font-size:12px;flex-shrink:0;">
                        {{ $iniciales }}
                    </div>
                    <span class="fw-semibold text-dark">{{ $empleado->name }}</span>
                </div>
            </div>

            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">DNI</span>
                <span class="text-dark">{{ $empleado->dni ?? '—' }}</span>
            </div>

            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">Correo electrónico</span>
                <span class="text-dark small">{{ $empleado->email }}</span>
            </div>

            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">Teléfono</span>
                <span class="text-dark">{{ $empleado->telefono ?? '—' }}</span>
            </div>

            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">Tipo de usuario</span>
                <span class="badge bg-light text-dark border px-3">{{ ucfirst($empleado->tipo) }}</span>
            </div>

            <div class="d-flex justify-content-between py-2">
                <span class="text-muted">Fecha de alta inicial</span>
                <span class="text-dark">{{ optional($empleado->fecha_alta)->format('d/m/Y') ?? '—' }}</span>
            </div>

        </div>
    </div>

    {{-- Botones de Acción --}}
    <form action="{{ route('empleados.baja', $empleado) }}" method="POST">
        @csrf
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-warning px-4 fw-bold shadow-sm" style="color: #451a03;">
                <i class="fas fa-user-minus me-2"></i>Confirmar Baja
            </button>
            <a href="{{ route('empleados.index') }}" class="btn btn-light border px-4">
                <i class="fas fa-times me-1"></i> Cancelar
            </a>
        </div>
    </form>

</div>
@endsection