@extends('layouts.app')

@section('titulo', 'Confirmación de baja de empleado')

@section('content')
<div class="container">

    <h1 class="mb-4">
        <i class="fas fa-user-minus"></i> Dar de baja empleado
    </h1>

    <div class="alert alert-warning" role="alert">
        <h4>
            <i class="fas fa-exclamation-triangle me-2"></i>
            ¿Estás seguro de que deseas dar de baja al empleado <span class="text-danger">{{ $empleado->name }}</span>?
        </h4>
        <br>
        <h5>Esta acción no elimina el empleado, solo lo marca como dado de baja. Se mantendrá su historial.</h5>
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
                {{ optional($empleado->fecha_alta)->format('d/m/Y') ?? '-' }}
            </p>

            <p>
                <strong><i class="fas fa-user-tag me-1"></i> Tipo:</strong>
                {{ ucfirst($empleado->tipo) }}
            </p>
        </div>
    </div>

    <form action="{{ route('empleados.baja', $empleado) }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-warning">
            <i class="fas fa-user-minus me-1"></i>
            Sí, dar de baja
        </button>

        <a href="{{ route('empleados.index') }}" class="btn btn-secondary">
            <i class="fas fa-times me-1"></i>
            No, cancelar
        </a>
    </form>
</div>

@endsection