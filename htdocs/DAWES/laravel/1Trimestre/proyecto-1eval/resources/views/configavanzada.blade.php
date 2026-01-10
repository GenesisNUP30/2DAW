@extends('layouts.plantilla01')

@section ('titulo', 'Configuración Avanzada')

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

    .form-control {
        border: 1px solid #cbd5e0;
        border-radius: 6px;
        padding: 0.5rem 0.75rem;
        font-size: 1rem;
        width: 100%;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15);
        outline: none;
    }

    .form-row {
        margin-bottom: 1.25rem;
    }

    .btn-submit {
        background: linear-gradient(135deg, #3182ce, #2b6cb0);
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
        background: linear-gradient(135deg, #2b6cb0, #2c5282);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(49, 130, 206, 0.3);
    }

    .mensaje-ok {
        background: #c6f6d5;
        color: #22543d;
        padding: 0.75rem;
        border-radius: 6px;
        margin-bottom: 1.5rem;
        border: 1px solid #9ae6b4;
    }
</style>
@endsection

@section('cuerpo')
<h1 class="mb-4">
    <i class="fas fa-cogs me-2"></i>Configuración Avanzada
</h1>

{{-- Mostrar mensaje solo si existe --}}
@if(!empty($mensaje))
<div class="mensaje-ok">
    {{ $mensaje }}
</div>
@endif

<form method="post">
    <div class="form-row">
        <label class="form-label">Provincia por defecto</label>
        <select name="provincia_defecto" class="form-select">
            <option value="">Seleccione provincia</option>
            {!! \App\Models\Funciones::mostrarProvincias($configavanzada->provincia_defecto) !!}
        </select>
    </div>

    <div class="form-row">
        <label class="form-label">Población por defecto</label>
        <input type="text" name="poblacion_defecto" class="form-control"
            value="{{ $configavanzada->poblacion_defecto }}">
    </div>

    <div class="form-row">
        <label class="form-label">Elementos por página</label>
        <input type="number" name="items_por_pagina" class="form-control"
            value="{{ $configavanzada->items_por_pagina }}">
        {!! \App\Models\Funciones::verErrores('items_por_pagina') !!}
    </div>

    <div class="form-row">
        <label class="form-label">Tiempo máximo de sesión inactiva (minutos)</label>
        <input type="number" name="tiempo_sesion_minutos" class="form-control"
            value="{{ $configavanzada->getTiempoSesionMinutos() }}">
        {!! \App\Models\Funciones::verErrores('tiempo_sesion_minutos') !!}
    </div>

    <div class="form-row">
        <label class="form-label">Tema visual</label>
        <select name="tema" class="form-control">
            <option value="claro" {{ $configavanzada->tema == 'claro' ? 'selected' : '' }}>Claro</option>
            <option value="oscuro" {{ $configavanzada->tema == 'oscuro' ? 'selected' : '' }}>Oscuro</option>
        </select>
    </div>

    <div class="mt-4">
        <button type="submit" class="btn-submit">
            <i class="fas fa-save me-1"></i>Guardar configuración
        </button>
    </div>

</form>

@endsection