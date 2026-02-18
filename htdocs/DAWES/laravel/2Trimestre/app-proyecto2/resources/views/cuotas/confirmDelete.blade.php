@extends('layouts.app')

@section('titulo', 'Confirmación de eliminación de cuota')

@section('content')
<div class="container">

    <h1 class="mb-4">
        <i class="fas fa-trash-alt"></i> Eliminar cuota
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
            ¿Estás seguro de que deseas eliminar la cuota número <span class="text-danger">{{ $cuota->id}}</span>?
        </h4>
        <br>
        <h5>Esta acción no se puede deshacer.</h5>
    </div>

    <div class="alert alert-primary" role="alert">
        <h4><i class="fas fa-info-circle me-1"></i>Información de la cuota:</h4>
        <hr>

        <div class="card-body">
            <p>
                <strong><i class="fas fa-user me-1"></i> Cliente:</strong>
                {{ $cuota->cliente->nombre ?? '-' }}
            </p>

            <p>
                <i class="fas fa-sticky-note me-1"></i> Concepto:</strong>
                {{ $cuota->concepto }}
            </p>

            <p>
                <strong><i class="fas fa-calendar-day me-1"></i> Fecha de emisión:</strong>
                {{ \Carbon\Carbon::parse($cuota->fecha_emision)->format('d/m/Y') }}
            </p>

            <p>
                <strong><i class="fas fa-phone me-1"></i> Importe:</strong>
                {{ $cuota->importe ?? '-' }}
            </p>

            <p>
                <strong><i class="fas fa-hand-holding-usd me-1"></i> Fecha de pago:</strong>
                {{ \Carbon\Carbon::parse($cuota->fecha_pago)->format('d/m/Y') }}
            </p>

            <p>
                <strong><i class="far fa-sticky-note me-1"></i> Notas:</strong>
                {{ ucfirst($cuota->notas) }}
            </p>
        </div>
    </div>
    <form action="{{ route ('cuotas.destroy', $cuota) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">
            <i class="fas fa-trash-alt me-1"></i>
            Sí, eliminar cuota
        </button>

        <a href="{{ route('cuotas.index') }}" class="btn btn-secondary">
            <i class="fas fa-times me-1"></i>
            No, cancelar
        </a>
    </form>
    @endsection