@extends('layouts.app')

@section('titulo', 'Reactivar empleado')

@section('content')
<div class="container py-3">

    {{-- Cabecera --}}
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h2 class="fw-bold m-0 text-dark">Reactivar empleado</h2>
        </div>
        <a href="{{ route('empleados.index') }}" class="btn btn-sm btn-light border">
            <i class="fas fa-arrow-left me-1"></i> Volver al listado
        </a>
    </div>

    {{-- Aviso de Acción Positiva (Estilo Verde/Info) --}}
    <div class="card border mb-3" style="border-color: #a7f3d0 !important; border-radius: 12px; background: #f0fdf4;">
        <div class="card-body d-flex align-items-start gap-3 py-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                 style="width:40px;height:40px;background:#d1fae5;">
                <i class="fas fa-user-plus text-success"></i>
            </div>
            <div>
                <p class="fw-semibold text-success mb-1" style="color: #065f46 !important;">Confirmar reactivación de cuenta</p>
                <p class="text-muted mb-0">
                    Estás a punto de reactivar a <strong>{{ $empleado->name }}</strong>. 
                    Al hacerlo, el empleado volverá a aparecer en los listados activos, podrá ser 
                    <strong>asignado a nuevas tareas</strong> y recuperará sus permisos de acceso al sistema.
                </p>
            </div>
        </div>
    </div>

    {{-- Resumen del empleado --}}
    <div class="card border shadow-sm mb-4" style="border-color: #e5e7eb !important; border-radius: 12px;">
        <div class="card-header bg-light border-bottom py-2 px-3"
             style="font-size:.7rem; font-weight:600; text-transform:uppercase; letter-spacing:.06em; color:#6b7280;">
            Información del registro
        </div>
        <div class="card-body px-3 py-2">

            {{-- Avatar e Iniciales (En tonos verdes) --}}
            <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                <span class="text-muted">Empleado</span>
                <div class="d-flex align-items-center gap-2">
                    @php
                        $iniciales = collect(explode(' ', $empleado->name))
                            ->take(2)->map(fn($p) => strtoupper($p[0]))->implode('');
                    @endphp
                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-semibold"
                         style="width:32px;height:32px;background:#d1fae5;color:#065f46;font-size:12px;flex-shrink:0;">
                        {{ $iniciales }}
                    </div>
                    <span class="fw-semibold text-dark">{{ $empleado->name }}</span>
                </div>
            </div>

            <div class="row border-bottom py-2">
                <div class="col-6">
                    <span class="text-muted d-block small">DNI</span>
                    <span class="text-dark">{{ $empleado->dni ?? '—' }}</span>
                </div>
                <div class="col-6 text-end">
                    <span class="text-muted d-block small">Tipo</span>
                    <span class="badge bg-white text-success border-success border px-2">{{ ucfirst($empleado->tipo) }}</span>
                </div>
            </div>

            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">Correo electrónico</span>
                <span class="text-dark">{{ $empleado->email }}</span>
            </div>

            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">Fecha de alta original</span>
                <span class="text-dark">{{ optional($empleado->fecha_alta)->format('d/m/Y') ?? '—' }}</span>
            </div>

            <div class="d-flex justify-content-between py-2 text-danger">
                <span><i class="fas fa-calendar-times me-1"></i> Dado de baja el</span>
                <span class="fw-bold">{{ optional($empleado->fecha_baja)->format('d/m/Y') ?? '—' }}</span>
            </div>

        </div>
    </div>

    {{-- Botones de Acción --}}
    <form action="{{ route('empleados.alta', $empleado) }}" method="POST">
        @csrf
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success px-4 shadow-sm fw-bold">
                <i class="fas fa-check-circle me-2"></i>Reactivar empleado
            </button>
            <a href="{{ route('empleados.index') }}" class="btn btn-light border px-4">
                <i class="fas fa-times me-1"></i> Cancelar
            </a>
        </div>
    </form>

</div>
@endsection