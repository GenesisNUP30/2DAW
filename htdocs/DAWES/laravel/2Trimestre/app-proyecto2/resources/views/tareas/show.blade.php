@extends('layouts.app')

@section('titulo', 'Detalle de la tarea')

@section('content')
<div class="container">

    <h1 class="mb-4">
        <i class="fas fa-tasks me-2"></i>
        Detalle de la tarea ID: {{ $tarea->id }}
    </h1>

    {{-- MENSAJES --}}
    @if (session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle me-1"></i>
        {{ session('success') }}
    </div>
    @endif

    {{-- INFORMACIÓN GENERAL --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-light">
            <i class="fas fa-info-circle me-1"></i>
            <strong>Información general</strong>
        </div>

        <div class="card-body">
            <p>
                <strong><i class="fas fa-user me-1"></i> Cliente:</strong>
                {{ $tarea->cliente->nombre }}
            </p>

            <p>
                <strong><i class="fas fa-clipboard-list me-1"></i> Descripción:</strong><br>
                {{ $tarea->descripcion }}
            </p>

            <p>
                <strong><i class="fas fa-flag me-1"></i> Estado:</strong>
                @switch($tarea->estado)
                @case('B')
                <span class="badge bg-secondary">Esperando aprobación</span>
                @break
                @case('P')
                <span class="badge bg-warning text-dark">Pendiente</span>
                @break
                @case('R')
                <span class="badge bg-success">Realizada</span>
                @break
                @case('C')
                <span class="badge bg-danger">Cancelada</span>
                @break
                @endswitch
            </p>

            <p>
                <strong><i class="fas fa-calendar-plus me-1"></i> Fecha creación:</strong>
                {{ \Carbon\Carbon::parse($tarea->fecha_creacion)->format('d/m/Y H:i') }}
            </p>

            <p>
                <strong><i class="fas fa-calendar-check me-1"></i> Fecha realización:</strong>
                {{ optional($tarea->fecha_realizacion)->format('d/m/Y') ?? '—' }}
            </p>

            <p>
                <strong><i class="fas fa-user-cog me-1"></i> Operario asignado:</strong>
                {{ $tarea->operario->nombre ?? '—' }}
            </p>
        </div>
    </div>

    {{-- DIRECCIÓN --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-light">
            <i class="fas fa-map-marker-alt me-1"></i>
            <strong>Dirección</strong>
        </div>

        <div class="card-body">
            <p>{{ $tarea->direccion ?? '—' }}</p>
            <p>
                {{ $tarea->codigo_postal ?? '—' }}
                {{ $tarea->poblacion ?? '' }}
                ({{ $tarea->provincia ?? '—' }})
            </p>
        </div>
    </div>

    {{-- ANOTACIONES --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-light">
            <i class="fas fa-clipboard me-2"></i>
            <strong>Anotaciones</strong>
        </div>

        <div class="card-body">
            <p><strong>Anotaciones anteriores:</strong></p>
            <p class="text-muted">
                {{ $tarea->anotaciones_anteriores ?: '—' }}
            </p>
            <hr>
            <p><strong>Anotaciones posteriores:</strong></p>
            <p class="text-muted">
                {{ $tarea->anotaciones_posteriores ?: '—' }}
            </p>
            <hr>
            <p>
                <strong><i class="fas fa-file me-1"></i> Fichero resumen:</strong><br>

                @if ($tarea->fichero_resumen)
                <a href="{{ route('tareas.downloadFile', $tarea) }}"
                    class="btn btn-outline-primary btn-sm mt-1">
                    <i class="fas fa-file-download me-1"></i>
                    Descargar fichero
                </a>
                @else
                <span class="text-muted">
                    <em>No hay fichero adjunto</em>
                </span>
                @endif
            </p>

        </div>
    </div>

    {{-- ACCIONES --}}
    <div class="d-flex gap-2 mb-4">
        <a href="{{ route('tareas.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
    </div>

</div>
@endsection