@extends('layouts.plantilla01')

@section('titulo', 'Completar Tarea')

@section('estilos')
<style>
    .error {
        color: #e53e3e;
        font-size: 0.875rem;
        margin-top: 0.25rem;
        display: block;
    }

    .form-label {
        font-weight: 600;
        margin-top: 1.25rem;
        color: #2d3748;
        display: block;
    }

    .form-control,
    .form-check-input {
        border: 1px solid #cbd5e0;
        border-radius: 6px;
        padding: 0.5rem 0.75rem;
        font-size: 1rem;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15);
        outline: none;
    }

    .form-control[readonly] {
        background-color: #f8f9fa;
        color: #4a5568;
    }

    .form-check {
        margin: 0.5rem 0;
    }

    .form-check-label {
        margin-left: 0.5rem;
        font-weight: 500;
        color: #2d3748;
    }

    .btn-submit {
        background: linear-gradient(135deg, #38a169, #2f855a);
        color: white;
        border: none;
        padding: 0.6rem 1.5rem;
        font-size: 1rem;
        font-weight: 600;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-submit:hover {
        background: linear-gradient(135deg, #2f855a, #276749);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(56, 161, 105, 0.3);
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

    .form-row {
        margin-bottom: 1.25rem;
    }

    textarea.form-control {
        min-height: 100px;
    }
    
</style>
@endsection

@section('cuerpo')
<h1 class="mb-4">
    <i class="fas fa-check-circle me-2"></i>Completar Tarea
</h1>

<form action="{{ url('completar/' . $id) }}" method="POST" enctype="multipart/form-data">
    <div class="form-row">
        <label class="form-label">NIF/CIF:</label>
        <input type="text" name="nif_cif" class="form-control" value="{{ $nif_cif }}" readonly>
    </div>

    <div class="form-row">
        <label class="form-label">Persona de contacto:</label>
        <input type="text" name="persona_contacto" class="form-control" value="{{ $persona_contacto }}" readonly>
    </div>

    <div class="form-row">
        <label class="form-label">Descripción de la tarea:</label>
        <textarea name="descripcion" class="form-control" readonly>{{ $descripcion }}</textarea>
    </div>

    <div class="form-row">
        <label class="form-label">Estado:</label>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="estado" value="R" id="estadoR" checked>
            <label class="form-check-label" for="estadoR">Completada</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="estado" value="C" id="estadoC" {{ $estado == "C" ? "checked" : "" }}>
            <label class="form-check-label" for="estadoC">Cancelada</label>
        </div>
        {!! \App\Models\Funciones::verErrores('estado') !!}
    </div>

    <div class="form-row">
        @php
        $esCompletada = old('estado', 'R') == 'R';
        @endphp
        <label class="form-label">Fecha de realización:</label>
        <input type="date" name="fecha_realizacion" class="form-control"
            value="{{ old('fecha_realizacion', $fecha_realizacion) }}" {{ $esCompletada ? '' : 'readonly' }}>
        {!! \App\Models\Funciones::verErrores('fecha_realizacion') !!}
    </div>

    <div class="form-row">
        <label class="form-label">Anotaciones anteriores:</label>
        <textarea id="anotaciones_anteriores" name="anotaciones_anteriores" class="form-control" readonly>{{ $anotaciones_anteriores }}</textarea>
    </div>

    <div class="form-row">
        <label class="form-label">Anotaciones posteriores:</label>
        <textarea id="anotaciones_posteriores" name="anotaciones_posteriores" class="form-control">{{ $anotaciones_posteriores }}</textarea>
        {!! \App\Models\Funciones::verErrores('anotaciones_posteriores') !!}
    </div>

    @if($esCompletada)
    <div class="form-row">
        <label class="form-label">Fichero resumen:</label>
        <input type="file" id="fichero_resumen" name="fichero_resumen" class="form-control">
        {!! \App\Models\Funciones::verErrores('fichero_resumen') !!}
    </div>
    @endif

    <div class="mt-4">
        <button type="submit" class="btn-submit">
            <i class="fas fa-check me-1"></i>Completar tarea
        </button>
        <a href="{{ url('/') }}" class="btn-cancel">
            <i class="fas fa-times me-1"></i>Cancelar
        </a>
    </div>
</form>
@endsection