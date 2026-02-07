@extends('layouts.app')

@section('titulo', 'Confirmación de alta de empleado')

@section('content')
<div class="container">

    <h1 class="mb-4">
        <i class="fas fa-user-check"></i> Reactivar empleado
    </h1>

    <div class="alert alert-info" role="alert">
        <h4>
            <i class="fas fa-info-circle me-2"></i>
            ¿Estás seguro de que deseas reactivar al empleado <span class="text-primary">{{ $empleado->name }}</span>?
        </h4>
        <br>
        <h5>Esta acción volverá a activar al empleado para que pueda acceder al sistema nuevamente.</h5>
    </div>

    <div class="alert alert-primary" role="alert">
        <h4><i class="fas fa-info-circle me-1"></i>Información del empleado:</h4>
        <hr>

        <div class="card-body">
            <p>
                <strong><i class="far fa-id-card"></i> DNI:</strong>
                {{ $empleado->dni ?? '-' }}
            </p>

            <p>
                <strong><i class="fas fa-user me-1"></i> Nombre:</strong>
                {{ $empleado->name }}
            </p>

            <p>
                <strong><i class="fas fa-at"></i> Correo electrónico:</strong>
                {{ $empleado->email }}
            </p>

            <p>
                <strong><i class="fas fa-phone me-1"></i> Teléfono:</strong>
                {{ $empleado->telefono ?? '-' }}
            </p>

            <p>
                <strong><i class="fas fa-calendar-check me-1"></i> Fecha de alta:</strong>
                {{ \Carbon\Carbon::parse($empleado->fecha_alta)->format('d/m/Y') }}
            </p>

            <p>
                <strong><i class="fas fa-calendar-times me-1"></i> Fecha de baja:</strong>
                {{ \Carbon\Carbon::parse($empleado->fecha_baja)->format('d/m/Y') }}
            </p>

            <p>
                <strong><i class="fas fa-user-tag me-1"></i> Tipo:</strong>
                {{ ucfirst($empleado->tipo) }}
            </p>
        </div>
    </div>

    <form action="{{ route('empleados.alta', $empleado) }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-success">
            <i class="fas fa-user-check me-1"></i>
            Sí, reactivar empleado
        </button>

        <a href="{{ route('empleados.index') }}" class="btn btn-secondary">
            <i class="fas fa-times me-1"></i>
            No, cancelar
        </a>
    </form>
</div>

@endsection