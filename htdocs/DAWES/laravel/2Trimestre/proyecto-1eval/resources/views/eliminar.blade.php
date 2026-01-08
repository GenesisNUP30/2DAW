@extends('layouts.plantilla01')

@section('titulo', 'Eliminar Tarea')

@section('estilos')
<style>
    .alert-warning {
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        color: #856404;
        padding: 1.25rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }

    .tarea-datos {
        background: #f7f7f7;
        border: 1px solid #cbd5e0;
        border-radius: 6px;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }

    .tarea-datos p {
        margin: 0.25rem 0;
    }

    .btn-confirm {
        background: linear-gradient(135deg, #e53e3e, #c53030);
        color: white;
        border: none;
        padding: 0.6rem 1.5rem;
        font-size: 1rem;
        font-weight: 600;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-confirm:hover {
        background: linear-gradient(135deg, #c53030, #9b2c2c);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(229, 62, 62, 0.3);
    }

    .btn-cancel {
        background: linear-gradient(135deg, #718096, #4a5568);
        color: white;
        text-decoration: none;
        padding: 0.6rem 1.5rem;
        font-size: 1rem;
        font-weight: 600;
        border-radius: 6px;
        display: inline-block;
        transition: all 0.2s ease;
        margin-left: 0.75rem;
    }

    .btn-cancel:hover {
        background: linear-gradient(135deg, #4a5568, #2d3748);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(71, 80, 96, 0.3);
    }

    h1 {
        margin-bottom: 1.5rem;
    }
</style>
@endsection

@section('cuerpo')
<h1>
    <i class="fas fa-trash-alt me-2"></i>Eliminar Tarea
</h1>

<div class="alert-warning">
    <i class="fas fa-exclamation-triangle me-2"></i>
    <strong>¿Estás seguro de que deseas eliminar la tarea de <span class="text-danger">{{ $tarea['persona_contacto'] }}</span>?</strong>
    <br>
    Esta acción no se puede deshacer.
</div>

<div class="tarea-datos">
    <p><strong>NIF/CIF:</strong> {{ $tarea['nif_cif'] }}</p>
    <p><strong>Persona de contacto:</strong> {{ $tarea['persona_contacto'] }}</p>
    <p><strong>Teléfono:</strong> {{ $tarea['telefono'] }}</p>
    <p><strong>Correo:</strong> {{ $tarea['correo'] }}</p>
    <p><strong>Descripción:</strong> {{ $tarea['descripcion'] }}</p>
    <p><strong>Fecha de realización:</strong> {{ \App\Models\Funciones::cambiarFormatoFecha($tarea['fecha_realizacion']) }}</p>
    <p><strong>Operario encargado:</strong> {{ $tarea['operario_encargado'] }}</p>
    <p><strong>Estado:</strong> {{ $tarea['estado'] }}</p>
</div>

<form action="{{ url('eliminar/' . $tarea['id']) }}" method="POST">
    @csrf
    <button type="submit" class="btn-confirm">
        <i class="fas fa-check-circle me-1"></i>Sí, eliminar
    </button>
    <a href="{{ url('/') }}" class="btn-cancel">
        <i class="fas fa-times me-1"></i>Cancelar
    </a>
</form>
@endsection
