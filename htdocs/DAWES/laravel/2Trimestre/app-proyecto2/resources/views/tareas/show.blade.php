@extends('layouts.app')

@section('titulo', 'Detalle de la tarea')

@section('content')
<div class="container">

    <h1 class="mb-4">
        <i class="fas fa-tasks me-2"></i> Detalle de la tarea ID: {{ $tarea->id }}
    </h1>

    {{-- MENSAJES --}}
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-info-circle me-1"></i><strong>Información general</strong>
        </div>

        <div class="card-body">
            <p><strong>Cliente:</strong> {{ $tarea->cliente->nombre }}</p>
            <p><strong>Descripción:</strong> {{ $tarea->descripcion }}</p>

            <p>
                <strong>Estado:</strong>
                @if ($tarea->estado === 'P')
                <span class="badge bg-warning text-dark">Pendiente</span>
                @elseif ($tarea->estado === 'R')
                <span class="badge bg-info">Realizada</span>
                @else
                <span class="badge bg-success">Cerrada</span>
                @endif
            </p>

            <p><strong>Fecha creación:</strong> {{ $tarea->fecha_creacion }}</p>
            <p><strong>Fecha realización:</strong> {{ optional($tarea->fecha_realizacion)->format('d/m/Y') }}</p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-map-marker-alt me-1"></i><strong>Dirección</strong>
        </div>

        <div class="card-body">
            <p>{{ $tarea->direccion }}</p>
            <p>{{ $tarea->codigo_postal }} - {{ $tarea->poblacion }} ({{ $tarea->provincia }})</p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-clipboard me-2"></i><strong>Anotaciones</strong>
        </div>

        <div class="card-body">
            <p><strong>Anteriores:</strong></p>
            <p>{{ $tarea->anotaciones_anteriores ?? '—' }}</p>

            <hr>

            <p><strong>Posteriores:</strong></p>
            <p>{{ $tarea->anotaciones_posteriores ?? '—' }}</p>
        </div>
    </div>

    {{-- ACCIONES --}}
    <div class="d-flex gap-2">

        <a href="{{ route('tareas.index') }}" class="btn btn-secondary">
            Volver
        </a>

        {{-- ADMIN --}}
        @if (Auth::user()->isAdmin())
        <a href="{{ route('tareas.edit', $tarea) }}" class="btn btn-primary">
            Editar
        </a>

        <a href="{{ route('tareas.confirmDelete', $tarea) }}" class="btn btn-danger">
            Eliminar
        </a>
        @endif

        {{-- OPERARIO --}}
        @if (Auth::user()->isEmpleado() && $tarea->estado === 'P')
        <a href="{{ route('tareas.completeForm', $tarea) }}" class="btn btn-success">
            Completar tarea
        </a>
        @endif

    </div>

</div>
@endsection