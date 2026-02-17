@extends('layouts.app')

@section('titulo', 'Confirmación de baja de cliente')

@section('content')
<div class="container">

    <h1 class="mb-4">
        <i class="fas fa-user-minus"></i> Dar de baja al cliente
    </h1>

    <div class="alert alert-warning" role="alert">
        <h4>
            <i class="fas fa-exclamation-triangle me-2"></i>
            ¿Estás seguro de que deseas dar de baja al cliente <span class="text-danger">{{ $cliente->name }}</span>?
        </h4>
        <br>
        <h5>Esta acción no elimina el cliente, solo lo marca como dado de baja. Se mantendrá su historial.</h5>
    </div>

    <div class="alert alert-primary" role="alert">
        <h4><i class="fas fa-info-circle me-1"></i>Información del cliente:</h4>
        <hr>

        <div class="card-body">
            <p>
                <strong><i class="far fa-id-card"></i> CIF:</strong>
                {{ $cliente->cif ?? '-' }}
            </p>

            <p>
                <strong><i class="fas fa-user me-1"></i> Nombre:</strong>
                {{ $cliente->nombre }}
            </p>

            <p>
                <strong><i class="fas fa-phone me-1"></i> Teléfono:</strong>
                {{ $cliente->telefono ?? '-' }}
            </p>

            <p>
                <strong><i class="fas fa-at"></i> Correo electrónico:</strong>
                {{ $cliente->correo }}
            </p>

            <p>
                <strong><i class="fas fa-credit-card me-1"></i> Cuenta corriente:</strong>
                {{ $cliente->cuenta_corriente }}
            </p>

            <p>
                <strong><i class="fas fa-globe me-1"></i> País:</strong>
                {{ $cliente->paisRelacion ? $cliente->paisRelacion->nombre : $cliente->pais }}
            </p>

            <p>
                <strong><i class="fas fa-money-bill-wave me-1"></i> Moneda:</strong>
                {{ $cliente->moneda }}
            </p>

            <p>
                <strong><i class="fas fa-euro-sign me-1"></i> Importe cuota:</strong>
                {{ number_format($cliente->importe_cuota_mensual, 2, ',', '.') }} €
            </p>

            <p>
                <strong><i class="fas fa-calendar-check me-1"></i> Fecha de alta:</strong>
                {{ \Carbon\Carbon::parse($cliente->fecha_alta)->format('d/m/Y') }}
            </p>
        </div>
    </div>

    <form action="{{ route('clientes.baja', $cliente) }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-warning">
            <i class="fas fa-user-minus me-1"></i>
            Sí, dar de baja
        </button>

        <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
            <i class="fas fa-times me-1"></i>
            No, cancelar
        </a>
    </form>
</div>

@endsection