@extends('layouts.app')

@section('titulo', 'Confirmación de eliminación de tarea')

@section('content')
<div class="container">

    <h1 class="mb-4">
        <i class="fas fa-trash-alt me-2"></i> Eliminar Tarea
    </h1>

    {{-- ERRORES --}}
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="alert alert-warning" role="alert">

        <h4>
            <i class="fas fa-exclamation-triangle me-2"></i>
            ¿Estás seguro de que deseas eliminar la tarea número <span class="text-danger">{{ $tarea->id}}</span>?
        </h4>
        <br>
        <h5>Esta acción no se puede deshacer.</h5>
    </div>

    <div class="alert alert-primary" role="alert">
        <h4><i class="fas fa-info-circle me-1"></i>Información de la tarea:</h4>
        <hr>

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

    <form action="{{ route('tareas.destroy', $tarea) }}" method="POST">
        @csrf
        @method('DELETE')

        <button type="submit" class="btn btn-danger">
            <i class="fas fa-trash-alt me-1"></i>
            Sí, eliminar tarea
        </button>

        <a href="{{ route('tareas.index') }}" class="btn btn-secondary">
            <i class="fas fa-times me-1"></i>
            No, cancelar
        </a>
    </form>

</div>
@endsection