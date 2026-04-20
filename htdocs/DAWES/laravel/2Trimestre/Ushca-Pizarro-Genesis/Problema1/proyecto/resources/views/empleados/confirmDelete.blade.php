@extends('layouts.app')

@section('titulo', 'Eliminar empleado')

@section('content')
<div class="container py-3">

    {{-- Cabecera --}}
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h2 class="fw-bold m-0 text-dark">Eliminar empleado</h2>
        </div>
        <a href="{{ route('empleados.index') }}" class="btn btn-sm btn-light border">
            <i class="fas fa-arrow-left me-1"></i> Volver al listado
        </a>
    </div>

    {{-- Errores --}}
    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Aviso de acción destructiva --}}
    <div class="card border mb-3" style="border-color: #fca5a5 !important; border-radius: 12px; background: #fff7f7;">
        <div class="card-body d-flex align-items-start gap-3 py-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                 style="width:40px;height:40px;background:#fee2e2;">
                <i class="fas fa-exclamation-triangle text-danger"></i>
            </div>
            <div>
                <p class="fw-semibold text-danger mb-1">Esta acción es irreversible</p>
                <p class="text-muted mb-0">
                    Estás a punto de eliminar permanentemente al empleado 
                    <strong>{{ $empleado->name }}</strong> (ID: {{ $empleado->id }}). 
                    Se perderá todo el acceso y registro asociado a este usuario.
                </p>
            </div>
        </div>
    </div>

    {{-- Resumen del empleado --}}
    <div class="card border shadow-sm mb-4" style="border-color: #e5e7eb !important; border-radius: 12px;">
        <div class="card-header bg-light border-bottom py-2 px-3"
             style="font-size:.7rem; font-weight:600; text-transform:uppercase; letter-spacing:.06em; color:#6b7280;">
            Información del empleado
        </div>
        <div class="card-body px-3 py-2">

            {{-- Fila con Avatar e Iniciales --}}
            <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                <span class="text-muted">Nombre Completo</span>
                <div class="d-flex align-items-center gap-2">
                    @php
                        $iniciales = collect(explode(' ', $empleado->name))
                            ->take(2)->map(fn($p) => strtoupper($p[0]))->implode('');
                    @endphp
                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-semibold"
                         style="width:32px;height:32px;background:#e0f2fe;color:#0369a1;font-size:12px;flex-shrink:0;">
                        {{ $iniciales }}
                    </div>
                    <span class="fw-semibold text-dark">{{ $empleado->name }}</span>
                </div>
            </div>

            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">DNI / Identificación</span>
                <span class="text-dark">{{ $empleado->dni }}</span>
            </div>

            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">Correo electrónico</span>
                <span class="text-dark">{{ $empleado->email }}</span>
            </div>

            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">Teléfono</span>
                <span class="text-dark">{{ $empleado->telefono ?? '—' }}</span>
            </div>

            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">Fecha de alta</span>
                <span class="text-dark">{{ optional($empleado->fecha_alta)->format('d/m/Y') ?? '—' }}</span>
            </div>

            <div class="d-flex justify-content-between py-2">
                <span class="text-muted">Dirección</span>
                <span class="text-end text-dark" style="max-width: 60%;">{{ $empleado->direccion ?? '—' }}</span>
            </div>

        </div>
    </div>

    {{-- Botones de Acción --}}
    <form action="{{ route('empleados.destroy', $empleado) }}" method="POST">
        @csrf
        @method('DELETE')
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-danger px-4">
                <i class="fas fa-trash-alt me-2"></i>Eliminar empleado
            </button>
            <a href="{{ route('empleados.index') }}" class="btn btn-light border px-4">
                <i class="fas fa-times me-1"></i> Cancelar
            </a>
        </div>
    </form>

</div>
@endsection