@extends('layouts.app')

@section('titulo', 'Eliminar tarea')

@section('content')
<div class="container py-3">

    {{-- Cabecera --}}
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h2 class="fw-bold m-0">Eliminar tarea</h2>
        </div>
        <a href="{{ route('tareas.index') }}" class="btn btn-sm btn-light border">
            <i class="fas fa-arrow-left me-1"></i> Volver al listado
        </a>
    </div>

    {{-- Errores --}}
    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
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
                <p class="fw-semibold text-danger mb-1">Esta acción no se puede deshacer</p>
                <p class="text-muted mb-0">
                    Estás a punto de eliminar permanentemente la tarea
                    <strong>nº {{ $tarea->id }}</strong> del cliente
                    <strong>{{ $tarea->cliente->nombre }}</strong>.
                </p>
            </div>
        </div>
    </div>

    {{-- Resumen de la tarea --}}
    <div class="card border shadow-sm mb-4" style="border-color: #e5e7eb !important; border-radius: 12px;">
        <div class="card-header bg-light border-bottom py-2 px-3"
             style="font-size:.7rem; font-weight:600; text-transform:uppercase; letter-spacing:.06em; color:#6b7280;">
            Información de la tarea
        </div>
        <div class="card-body px-3 py-2">

            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">Cliente</span>
                <span class="fw-semibold">{{ $tarea->cliente->nombre ?? '—' }}</span>
            </div>

            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                <span class="text-muted">Estado</span>
                @php
                    $badges = [
                        'B' => ['bg-secondary bg-opacity-10 text-secondary border-secondary', 'En espera'],
                        'P' => ['bg-warning bg-opacity-10 text-warning border-warning',       'Pendiente'],
                        'R' => ['bg-success bg-opacity-10 text-success border-success',       'Realizada'],
                        'C' => ['bg-danger bg-opacity-10 text-danger border-danger',          'Cancelada'],
                    ];
                    [$badgeClase, $badgeTexto] = $badges[$tarea->estado] ?? ['bg-light text-dark', $tarea->estado];
                @endphp
                <span class="badge rounded-pill border {{ $badgeClase }}" style="font-size:.8rem; padding: 5px 14px;">
                    {{ $badgeTexto }}
                </span>
            </div>

            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">Operario</span>
                @if($tarea->operario)
                @php
                    $iniciales = collect(explode(' ', $tarea->operario->name))
                        ->take(2)->map(fn($p) => strtoupper($p[0]))->implode('');
                @endphp
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-semibold"
                         style="width:26px;height:26px;background:#e0f2fe;color:#0369a1;font-size:10px;flex-shrink:0;">
                        {{ $iniciales }}
                    </div>
                    <span>{{ $tarea->operario->name }}</span>
                </div>
                @else
                <span class="text-muted fst-italic">Sin asignar</span>
                @endif
            </div>

            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">Fecha creación</span>
                <span>{{ \Carbon\Carbon::parse($tarea->fecha_creacion)->format('d/m/Y H:i') }}</span>
            </div>

            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">Fecha realización</span>
                <span>{{ $tarea->fecha_realizacion?->format('d/m/Y') ?? '—' }}</span>
            </div>

            <div class="d-flex justify-content-between py-2">
                <span class="text-muted">Descripción</span>
                <span class="text-end" style="max-width: 60%;">{{ $tarea->descripcion }}</span>
            </div>

        </div>
    </div>

    {{-- Botones --}}
    <form action="{{ route('tareas.destroy', $tarea) }}" method="POST">
        @csrf
        @method('DELETE')
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-trash me-1"></i> Sí, eliminar tarea
            </button>
            <a href="{{ route('tareas.index') }}" class="btn btn-light border">
                <i class="fas fa-times me-1"></i> Cancelar
            </a>
        </div>
    </form>

</div>
@endsection