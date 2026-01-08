@extends('layouts.plantilla01')

@section('titulo', 'Detalle de la Tarea')

@section('estilos')
<style>
    .detalle-tarea {
        max-width: 800px;
        margin: 30px auto;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        padding: 20px 30px;
        font-size: 14px;
        color: #4a5568;
    }

    .detalle-tarea h2 {
        margin-bottom: 20px;
        font-size: 22px;
        color: #2c3e50;
    }

    .detalle-tarea .campo {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #e2e8f0;
    }

    .detalle-tarea .campo:last-child {
        border-bottom: none;
    }

    .detalle-tarea .campo .label {
        font-weight: 600;
        color: #2d3748;
    }

    .detalle-tarea .btn-volver {
        display: inline-block;
        margin-top: 20px;
        padding: 8px 16px;
        background: linear-gradient(135deg, #48bb78, #38a169);
        color: white;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .detalle-tarea .btn-volver:hover {
        background: linear-gradient(135deg, #38a169, #2f855a);
        transform: translateY(-2px);
        box-shadow: 0 3px 6px rgba(72, 187, 120, 0.3);
    }

    .detalle-tarea .seccion {
        margin-bottom: 20px;
    }

    .detalle-tarea .seccion h3 {
        font-size: 16px;
        color: #2b6cb0;
        margin-bottom: 10px;
        border-bottom: 1px solid #cbd5e0;
        padding-bottom: 4px;
    }
</style>
@endsection

@section('cuerpo')
<div class="detalle-tarea">
    <h2><i class="fas fa-tasks me-2"></i> Tarea ID: {{ $tarea['id'] }}</h2>

    <div class="seccion">
        <h3><i class="fas fa-user me-1"></i> Contacto</h3>
        <div class="campo">
            <span class="label">Persona:</span>
            <span>{{ $tarea['persona_contacto'] }}</span>
        </div>
        <div class="campo">
            <span class="label">Teléfono:</span>
            <span>{{ $tarea['telefono'] }}</span>
        </div>
        <div class="campo">
            <span class="label">Correo:</span>
            <span>{{ $tarea['correo'] }}</span>
        </div>
    </div>

    <div class="seccion">
        <h3><i class="fas fa-map-marker-alt me-1"></i> Dirección</h3>
        <div class="campo">
            <span class="label">Dirección:</span>
            <span>{{ $tarea['direccion'] }}</span>
        </div>
        <div class="campo">
            <span class="label">Población:</span>
            <span>{{ $tarea['poblacion'] }}</span>
        </div>
        <div class="campo">
            <span class="label">Código Postal:</span>
            <span>{{ $tarea['codigo_postal'] }}</span>
        </div>
        <div class="campo">
            <span class="label">Provincia:</span>
            <span>{{ $tarea['provincia'] }}</span>
        </div>
    </div>

    <div class="seccion">
        <h3><i class="fas fa-info-circle me-1"></i> Información de la Tarea</h3>
        <div class="campo">
            <span class="label">Descripción:</span>
            <span>{{ $tarea['descripcion'] }}</span>
        </div>
        <div class="campo">
            <span class="label">Estado:</span>
            <span>{{ $tarea['estado'] }}</span>
        </div>
        <div class="campo">
            <span class="label">Operario encargado:</span>
            <span>{{ $tarea['operario_encargado'] }}</span>
        </div>
        <div class="campo">
            <span class="label">Fecha de realización:</span>
            <span>{{ \App\Models\Funciones::cambiarFormatoFecha($tarea['fecha_realizacion']) }}</span>
        </div>
        <div class="campo">
            <span class="label">Anotaciones anteriores:</span>
            <span>{{ $tarea['anotaciones_anteriores'] }}</span>
        </div>
        <div class="campo">
            <span class="label">Anotaciones posteriores:</span>
            <span>{{ $tarea['anotaciones_posteriores'] }}</span>
        </div>
        @if (!empty($tarea['fichero_resumen']))
        <div class="campo">
            <span class="label">Fichero adjunto:</span>
            <span>
                <a href="{!! miurl('tarea/' . $tarea['id'] . '/fichero') !!}"
                    class="btn-volver"
                    style="margin-top:0; padding:6px 12px; font-size:13px;">
                    <i class="fas fa-file-download me-1"></i>
                    Descargar fichero
                </a>
            </span>
        </div>
        @else
        <div class="campo">
            <span class="label">Fichero adjunto:</span>
            <span class="text-muted"><em>No hay fichero adjunto</em></span>
        </div>
        @endif


    </div>

    <a href="{!! miurl('/') !!}" class="btn-volver"><i class="fas fa-arrow-left me-1"></i> Volver</a>
</div>
@endsection