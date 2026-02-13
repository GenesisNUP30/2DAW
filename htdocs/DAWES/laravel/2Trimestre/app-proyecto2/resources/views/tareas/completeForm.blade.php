@extends('layouts.app')

@section('titulo', 'Completar tarea')

@section('content')
<div class="container">

    <h1 class="mb-4">
        <i class="fas fa-check-circle me-2"></i> Completar tarea ID: {{ $tarea->id }}
    </h1>

    {{-- Información de la tarea --}}
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i> Información de la tarea:</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <p><strong><i class="fas fa-user me-2"></i>Persona de contacto:</strong> {{ $tarea->persona_contacto }}</p>
                    <p><strong><i class="fas fa-phone me-2"></i>Teléfono:</strong> {{ $tarea->telefono_contacto }}</p>
                    <p><strong><i class="fas fa-envelope me-2"></i>Correo:</strong> {{ $tarea->correo_contacto }}</p>
                </div>
                <div class="col-md-4">
                    <p><strong><i class="fas fa-map-marker-alt me-2"></i>Dirección:</strong> {{ $tarea->direccion ?? '-' }}</p>
                    <p><strong><i class="fas fa-city me-2"></i>Población:</strong> {{ $tarea->poblacion ?? '-' }}</p>
                    <p><strong><i class="fas fa-map me-2"></i>CP/Provincia:</strong> {{ $tarea->codigo_postal }} / {{ $tarea->provincia }}</p>
                </div>
                <div class="col-md-4">
                    <p><strong><i class="fas fa-sticky-note me-2"></i>Descripción:</strong></p>
                    <p class="text-muted">{{ $tarea->descripcion }}</p>

                    @if($tarea->anotaciones_anteriores)
                    <p><strong><i class="fas fa-sticky-note me-2"></i>Anotaciones anteriores:</strong></p>
                    <p class="text-muted">{{ $tarea->anotaciones_anteriores }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    {{-- ERRORES --}}
    @if ($errors->any())
    <div class="alert alert-danger">
        <strong>Por favor, corrige los siguientes errores:</strong>
    </div>
    @endif

    <form action="{{ route('tareas.complete', $tarea) }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- ESTADO --}}
        <div class="mb-3">
            <label class="form-label">Estado de la tarea: </label>
            <div class="form-check mb-2">
                <input class="form-check-input" type="radio" name="estado" id="estadoR" value="R" {{ old('estado', 'R') == 'R' ? 'checked' : '' }} required>
                <label class="form-check-label" for="estadoR">
                    <span class="badge bg-success">Realizada</span> - La tarea se ha completado correctamente
                </label>
            </div>
            <div class="form-check mb-2">
                <input class="form-check-input" type="radio" name="estado" id="estadoC" value="C" {{ old('estado') == 'C' ? 'checked' : '' }}>
                <label class="form-check-label" for="estadoC">
                    <span class="badge bg-danger">Cancelada</span> - La tarea no se ha podido realizar
                </label>
            </div>
            @error('estado')
            <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- ANOTACIONES POSTERIORES --}}
        <div class="mb-3">
            <label class="form-label">Anotaciones posteriores: </label>
            <textarea name="anotaciones_posteriores" class="form-control" rows="6" placeholder="Ej: Se ha reparado la avería, se han utilizado 2 metros de cable...">{{ old('anotaciones_posteriores') }}</textarea>
            @error('anotaciones_posteriores')
            <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
            <small class="text-muted">Anotaciones sobre el trabajo realizado (opcional).</small>
        </div>

        {{-- FECHA DE REALIZACIÓN --}}
        <div class="mb-3">
            <label class="form-label">Fecha de realización: </label>
            <input type="date" name="fecha_realizacion" class="form-control" value="{{ old('fecha_realizacion', date('Y-m-d')) }}">
            @error('fecha_realizacion')
            <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
            <small class="text-muted">
                @if(old('estado', 'R') == 'R')
                <strong>Obligatorio</strong> cuando el estado es "Realizada".
                @else
                Obligatorio solo si seleccionas "Realizada" como estado.
                @endif
            </small>
        </div>

        {{-- FICHERO RESUMEN --}}
        <div class="mb-3">
            <label class="form-label">Fichero resumen del trabajo realizado:</label>
            <input type="file" name="fichero_resumen" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
            @error('fichero_resumen')
            <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
            <small class="text-muted">
                @if(old('estado', 'R') == 'R')
                <strong>Obligatorio</strong> cuando el estado es "Realizada". Formatos permitidos: PDF, Word, JPG, PNG. Máximo 5MB.
                @else
                Obligatorio solo si seleccionas "Realizada" como estado. Formatos permitidos: PDF, Word, JPG, PNG. Máximo 5MB.
                @endif
            </small>
        </div>

        {{-- PREVISUALIZACIÓN DEL FICHERO (si ya existe) --}}
        @if($tarea->fichero_resumen && Storage::disk('private')->exists($tarea->fichero_resumen))
        <div class="mb-3">
            <label class="form-label">Fichero actual:</label>
            <div class="alert alert-info">
                <i class="fas fa-file me-2"></i>
                {{ basename($tarea->fichero_resumen) }}
                <a href="{{ route('tareas.downloadFile', $tarea) }}" class="btn btn-sm btn-primary ms-3">
                    <i class="fas fa-download me-1"></i> Descargar
                </a>
            </div>
        </div>
        @endif

        {{-- BOTONES --}}
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-check-circle me-1"></i>
                Guardar cambios
            </button>

            <a href="{{ route('tareas.index') }}" class="btn btn-secondary">
                <i class="fas fa-times me-1"></i>
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection