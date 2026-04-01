@extends('layouts.app')

@section('titulo', 'Completar tarea')

@section('content')
<div class="container py-3">

    {{-- Cabecera --}}
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h2 class="fw-bold m-0 text-dark">Completar tarea ID: {{ $tarea->id }}</h2>
            <p class="text-muted">Rellena los datos de la intervención realizada.</p>
        </div>
        <a href="{{ route('tareas.index') }}" class="btn btn-sm btn-light border px-3">
            <i class="fas fa-arrow-left me-1"></i> Volver al listado
        </a>
    </div>

    <div class="row">
        {{-- Información de la tarea (Referencia) --}}
        <div class="col-md-4 order-md-2">
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; background: #f8f9fa;">
                <div class="card-body">
                    <h6 class="fw-bold text-uppercase text-muted mb-3" style="font-size: 0.75rem;">Datos de contacto</h6>
                    <p class="mb-1"><strong>{{ $tarea->persona_contacto }}</strong></p>
                    <p class="text-muted mb-3">{{ $tarea->telefono_contacto }}<br>{{ $tarea->correo_contacto }}</p>

                    <h6 class="fw-bold text-uppercase text-muted mb-3" style="font-size: 0.75rem;">Ubicación</h6>
                    <p class="mb-3">{{ $tarea->direccion }}<br>{{ $tarea->poblacion }} ({{ $tarea->provincia }})</p>

                    <h6 class="fw-bold text-uppercase text-muted mb-2" style="font-size: 0.75rem;">Descripción original</h6>
                    <p class="text-muted small italic">"{{ $tarea->descripcion }}"</p>
                </div>
            </div>
        </div>

        {{-- Formulario de actualización --}}
        <div class="col-md-8 order-md-1">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body p-4">
                    <form action="{{ route('tareas.complete', $tarea) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            {{-- ESTADO --}}
                            <div class="col-md-6 mb-3">
                                <label for="estado" class="form-label fw-bold">Estado de la tarea</label>
                                <select name="estado" id="estado" class="form-select @error('estado') is-invalid @enderror" required>
                                    <option value="R" {{ old('estado', 'R') == 'R' ? 'selected' : '' }}>Realizada (Completada)</option>
                                    <option value="C" {{ old('estado') == 'C' ? 'selected' : '' }}>Cancelada (No realizada)</option>
                                </select>
                                @error('estado')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- FECHA_REALIZACION --}}
                            <div class="col-md-6 mb-3">
                                <label for="fecha_realizacion" class="form-label fw-bold">Fecha de realización</label>
                                <input type="date" name="fecha_realizacion" id="fecha_realizacion" 
                                       class="form-control @error('fecha_realizacion') is-invalid @enderror" 
                                       value="{{ old('fecha_realizacion') }}">
                                @error('fecha_realizacion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- ANOTACIONES_POSTERIORES --}}
                        <div class="mb-3">
                            <label for="anotaciones_posteriores" class="form-label fw-bold">Anotaciones posteriores</label>
                            <textarea name="anotaciones_posteriores" id="anotaciones_posteriores" 
                                      class="form-control @error('anotaciones_posteriores') is-invalid @enderror" 
                                      rows="5" placeholder="Explica brevemente el trabajo realizado...">{{ old('anotaciones_posteriores') }}</textarea>
                            @error('anotaciones_posteriores')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- FICHERO_RESUMEN --}}
                        <div class="mb-4">
                            <label for="fichero_resumen" class="form-label fw-bold">Fichero resumen (PDF o Imagen)</label>
                            <input type="file" name="fichero_resumen" id="fichero_resumen" 
                                   class="form-control @error('fichero_resumen') is-invalid @enderror">
                            <div class="form-text">Formatos permitidos: PDF, DOC, JPG, PNG (Máx 5MB).</div>
                            @error('fichero_resumen')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if($tarea->fichero_resumen)
                        <div class="alert alert-info py-2 px-3 d-flex align-items-center justify-content-between">
                            <span><i class="fas fa-file-download me-2"></i>Ya existe un fichero adjunto</span>
                            <a href="{{ route('tareas.downloadFile', $tarea) }}" class="btn btn-sm btn-primary">Descargar</a>
                        </div>
                        @endif

                        <div class="d-flex gap-2 pt-3 border-top">
                            <button type="submit" class="btn btn-success px-4 fw-bold shadow-sm">
                                <i class="fas fa-check-circle me-1"></i> Guardar cambios
                            </button>
                            <a href="{{ route('tareas.index') }}" class="btn btn-light border px-4">
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection