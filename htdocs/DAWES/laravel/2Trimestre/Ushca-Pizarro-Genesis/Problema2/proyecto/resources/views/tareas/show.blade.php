@extends('layouts.app')

@section('titulo', 'Detalle de la tarea')

@section('content')
<div class="container py-3">

    {{-- Mensajes --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Cabecera --}}
    <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
            <h2 class="fw-bold m-0">Detalle de tarea</h2>
            <small class="text-muted">
                ID #{{ $tarea->id }} · Creada el {{ \Carbon\Carbon::parse($tarea->fecha_creacion)->format('d/m/Y \a \l\a\s H:i') }}
            </small>
        </div>
        <a href="{{ route('tareas.index') }}" class="btn btn-sm btn-light border">
            <i class="fas fa-arrow-left me-1"></i> Volver al listado
        </a>
    </div>

    {{-- Estado destacado --}}
    @php
    $badges = [
    'B' => ['bg-secondary bg-opacity-10 text-secondary border-secondary', 'En espera'],
    'P' => ['bg-warning bg-opacity-10 text-warning border-warning', 'Pendiente'],
    'R' => ['bg-success bg-opacity-10 text-success border-success', 'Realizada'],
    'C' => ['bg-danger bg-opacity-10 text-danger border-danger', 'Cancelada'],
    ];
    [$badgeClase, $badgeTexto] = $badges[$tarea->estado] ?? ['bg-light text-dark', $tarea->estado];
    @endphp
    <div class="d-flex align-items-center gap-3 mb-4">
        <span class="badge rounded-pill border {{ $badgeClase }}" style="font-size:.8rem; padding: 6px 16px;">
            {{ $badgeTexto }}
        </span>
        @if($tarea->fecha_realizacion)
        <small class="text-muted">
            <i class="fas fa-calendar-check me-1"></i>
            Fecha de realización: {{ $tarea->fecha_realizacion->format('d/m/Y') }}
        </small>
        @endif
    </div>

    {{-- Grid principal: info general + contacto --}}
    <div class="row g-3 mb-3">

        <div class="col-md-6">
            <div class="card border shadow-sm h-100" style="border-color:#e5e7eb !important; border-radius:12px;">
                <div class="card-header bg-light border-bottom py-2 px-3"
                    style="font-size:.7rem; font-weight:600; text-transform:uppercase; letter-spacing:.06em; color:#6b7280;">
                    Información general
                </div>
                <div class="card-body px-3 py-2">

                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Cliente</span>
                        <span class="fw-semibold">{{ $tarea->cliente->nombre ?? '—' }}</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <span class="text-muted">Operario</span>
                        @if($tarea->operario)
                        <div class="d-flex align-items-center gap-2">
                            <span>{{ $tarea->operario->name }}</span>
                        </div>
                        @else
                        <span class="text-muted fst-italic">Sin asignar</span>
                        @endif
                    </div>

                    <div class="d-flex justify-content-between py-2">
                        <span class="text-muted ">Persona de contacto</span>
                        <span class=" fw-semibold">{{ $tarea->persona_contacto ?? '—' }}</span>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border shadow-sm h-100" style="border-color:#e5e7eb !important; border-radius:12px;">
                <div class="card-header bg-light border-bottom py-2 px-3"
                    style="font-size:.7rem; font-weight:600; text-transform:uppercase; letter-spacing:.06em; color:#6b7280;">
                    Contacto y dirección
                </div>
                <div class="card-body px-3 py-2">

                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Teléfono</span>
                        <span class="fw-semibold">{{ $tarea->telefono_contacto ?? '—' }}</span>
                    </div>

                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Correo</span>
                        <span class="fw-semibold">{{ $tarea->correo_contacto ?? '—' }}</span>
                    </div>

                    <div class="d-flex justify-content-between py-2">
                        <span class="text-muted">Dirección</span>
                        <span class="fw-semibold text-end">
                            {{ $tarea->direccion ?? '—' }}<br>
                            <span class="text-muted fw-normal">
                                {{ $tarea->codigo_postal }} {{ $tarea->poblacion }}
                                @if($tarea->provincia) ({{ $tarea->provincia }}) @endif
                            </span>
                        </span>
                    </div>

                </div>
            </div>
        </div>

    </div>

    {{-- Descripción --}}
    <div class="card border shadow-sm mb-3" style="border-color:#e5e7eb !important; border-radius:12px;">
        <div class="card-header bg-light border-bottom py-2 px-3"
            style="font-size:.7rem; font-weight:600; text-transform:uppercase; letter-spacing:.06em; color:#6b7280;">
            Descripción
        </div>
        <div class="card-body px-3 py-3">
            <p class="mb-0" style="line-height:1.7;">{{ $tarea->descripcion }}</p>
        </div>
    </div>

    {{-- Anotaciones --}}
    <div class="row g-3 mb-3">

        <div class="col-md-6">
            <div class="card border shadow-sm h-100" style="border-color:#e5e7eb !important; border-radius:12px;">
                <div class="card-header bg-light border-bottom py-2 px-3"
                    style="font-size:.7rem; font-weight:600; text-transform:uppercase; letter-spacing:.06em; color:#6b7280;">
                    Anotaciones anteriores
                </div>
                <div class="card-body px-3 py-3">
                    @if($tarea->anotaciones_anteriores)
                    <p class="mb-0" style="line-height:1.7;">{{ $tarea->anotaciones_anteriores }}</p>
                    @else
                    <p class="mb-0 text-muted fst-italic">Sin anotaciones.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border shadow-sm h-100" style="border-color:#e5e7eb !important; border-radius:12px;">
                <div class="card-header bg-light border-bottom py-2 px-3"
                    style="font-size:.7rem; font-weight:600; text-transform:uppercase; letter-spacing:.06em; color:#6b7280;">
                    Anotaciones posteriores
                </div>
                <div class="card-body px-3 py-3">
                    @if($tarea->anotaciones_posteriores)
                    <p class="mb-0" style="line-height:1.7;">{{ $tarea->anotaciones_posteriores }}</p>
                    @else
                    <p class="mb-0 text-muted fst-italic">Sin anotaciones todavía.</p>
                    @endif
                </div>
            </div>
        </div>

    </div>

    {{-- Fichero adjunto --}}
    <div class="card border shadow-sm mb-4" style="border-color:#e5e7eb !important; border-radius:12px;">
        <div class="card-header bg-light border-bottom py-2 px-3"
            style="font-size:.7rem; font-weight:600; text-transform:uppercase; letter-spacing:.06em; color:#6b7280;">
            Fichero adjunto
        </div>
        <div class="card-body px-3 py-3 d-flex align-items-center justify-content-between">
            @if($tarea->fichero_resumen)
            <span class="text-muted">
                <i class="fas fa-paperclip me-1"></i>
                {{ basename($tarea->fichero_resumen) }}
            </span>
            <a href="{{ route('tareas.downloadFile', $tarea) }}" class="btn btn-sm btn-light border">
                <i class="fas fa-download me-1 text-info"></i>
                <span>Descargar</span>
            </a>
            @else
            <span class="text-muted fst-italic">No hay fichero adjunto para esta tarea.</span>
            @endif
        </div>
    </div>

    {{-- Acciones --}}
    <div class="d-flex gap-2 flex-wrap">
        @if(auth()->user()->isAdmin())

        <a href="{{ route('tareas.index') }}" class="btn btn-sm btn-light border">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
        <a href="{{ route('tareas.edit', $tarea) }}" class="btn btn-sm btn-light border">
            <i class="fas fa-pen text-warning me-1"></i> Editar
        </a>
        <a href="{{ route('tareas.confirmDelete', $tarea) }}" class="btn btn-sm btn-light border">
            <i class="fas fa-trash text-danger me-1"></i> Eliminar
        </a>
        @endif

        @if(auth()->user()->isOperario() && $tarea->estado === 'P')
        <a href="{{ route('tareas.completeForm', $tarea) }}" class="btn btn-sm btn-light border">
            <i class="fas fa-check text-success me-1"></i> Completar tarea
        </a>
        @endif
    </div>

</div>
@endsection