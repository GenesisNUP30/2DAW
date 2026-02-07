@extends('layouts.app')

@section('titulo', 'Confirmación de eliminación de empleado')

@section('content')
<div class="container">

    <h1 class="mb-4">
        <i class="fas fa-user-times"></i> Eliminar empleado
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
            ¿Estás seguro de que deseas eliminar el empleado número <span class="text-danger">{{ $empleado->id}}</span>?
        </h4>
        <br>
        <h5>Esta acción no se puede deshacer.</h5>
    </div>

    <div class="alert alert-primary" role="alert">
        <h4><i class="fas fa-info-circle me-1"></i>Información del empleado:</h4>
        <hr>

        <div class="card-body">
            <p>
                <strong><i class="far fa-id-card"></i> DNI:</strong>
                {{ $empleado->dni }}
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
                {{ $empleado->telefono }}
            </p>

            <p>
                <strong><i class="fas fa-map-marker-alt me-1"></i> Dirección:</strong>
                {{ $empleado->direccion }}
            </p>

            <p>
                <strong><i class="fas fa-calendar-check me-1"></i> Fecha de alta:</strong>
                {{ \Carbon\Carbon::parse($empleado->fecha_alta)->format('d/m/Y') }}
            </p>
        </div>
    </div>

    <form action="{{ route('empleados.destroy', $empleado) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">
            <i class="fas fa-trash-alt me-1"></i>
            Sí, eliminar empleado
        </button>

        <a href="{{ route('empleados.index') }}" class="btn btn-secondary">
            <i class="fas fa-times me-1"></i>
            No, cancelar
        </a>
    </form>
</div>

@endsection