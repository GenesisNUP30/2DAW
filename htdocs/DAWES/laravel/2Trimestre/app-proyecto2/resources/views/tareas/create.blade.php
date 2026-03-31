@extends('layouts.app')

@section('titulo', 'Crear tarea')

@section('content')
<div class="container py-4">

    {{-- Cabecera --}}
    <div class="row justify-content-center mb-4">
        <div class="col-lg-10">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h2 class="fw-bold m-0 text-dark">
                        <i class="fas fa-plus-circle me-2"></i>Nueva Tarea / Incidencia
                    </h2>
                    <p class="text-muted small mb-0">Registra una nueva intervención o reporte técnico.</p>
                </div>
                <a href="{{ route('tareas.index') }}" class="btn btn-sm btn-light border shadow-sm">
                    <i class="fas fa-arrow-left me-1"></i> Volver al listado
                </a>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <form method="POST" action="{{ route('tareas.store') }}">
                @csrf

                <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                    <div class="card-body p-4">
                        
                        {{-- SECCIÓN 1: CLIENTE Y CONTACTO --}}
                        <h5 class="fw-bold mb-4 text-primary border-bottom pb-2">
                            <i class="fas fa-user-tie me-2"></i>Información del Cliente y Contacto
                        </h5>
                        
                        <div class="row g-3 mb-4">
                            <div class="col-md-12">
                                <label class="form-label small fw-bold text-muted text-uppercase">Cliente</label>
                                <select name="cliente_id" class="form-select @error('cliente_id') is-invalid @enderror">
                                    <option value="" selected disabled>-- Selecciona cliente --</option>
                                    @foreach ($clientes as $cliente)
                                    <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                        {{ $cliente->nombre }} ({{ $cliente->cif }})
                                    </option>
                                    @endforeach
                                </select>
                                @error('cliente_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted text-uppercase">Persona de contacto</label>
                                <input type="text" name="persona_contacto" class="form-control @error('persona_contacto') is-invalid @enderror" value="{{ old('persona_contacto') }}" placeholder="Nombre del contacto">
                                @error('persona_contacto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted text-uppercase">Teléfono</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-phone-alt small text-muted"></i></span>
                                    <input type="text" name="telefono_contacto" class="form-control @error('telefono_contacto') is-invalid @enderror" value="{{ old('telefono_contacto') }}">
                                    @error('telefono_contacto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted text-uppercase">Correo electrónico</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-envelope small text-muted"></i></span>
                                    <input type="email" name="correo_contacto" class="form-control @error('correo_contacto') is-invalid @enderror" value="{{ old('correo_contacto') }}">
                                    @error('correo_contacto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- SECCIÓN 2: LOCALIZACIÓN --}}
                        <h5 class="fw-bold mb-4 text-primary border-bottom pb-2">
                            <i class="fas fa-map-marker-alt me-2"></i>Ubicación de la Tarea
                        </h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-12">
                                <label class="form-label small fw-bold text-muted text-uppercase">Dirección</label>
                                <input type="text" name="direccion" class="form-control @error('direccion') is-invalid @enderror" value="{{ old('direccion') }}" placeholder="Calle, número, piso...">
                                @error('direccion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted text-uppercase">Población</label>
                                <input type="text" name="poblacion" class="form-control @error('poblacion') is-invalid @enderror" value="{{ old('poblacion') }}">
                                @error('poblacion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted text-uppercase">Código postal</label>
                                <input type="text" name="codigo_postal" class="form-control @error('codigo_postal') is-invalid @enderror" value="{{ old('codigo_postal') }}">
                                @error('codigo_postal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted text-uppercase">Provincia</label>
                                <select name="provincia" class="form-select @error('provincia') is-invalid @enderror">
                                    <option value="">-- Selecciona provincia --</option>
                                    @foreach ($provincias as $codigo => $nombre)
                                    <option value="{{ $codigo }}" {{ old('provincia') == $codigo ? 'selected' : '' }}>
                                        {{ $nombre }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('provincia') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- SECCIÓN 3: DETALLES TÉCNICOS --}}
                        <h5 class="fw-bold mb-4 text-primary border-bottom pb-2">
                            <i class="fas fa-tools me-2"></i>Descripción y Asignación
                        </h5>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label small fw-bold text-muted text-uppercase">Descripción del problema</label>
                                <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" rows="3" placeholder="Describe detalladamente la incidencia...">{{ old('descripcion') }}</textarea>
                                @error('descripcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted text-uppercase">Estado Inicial</label>
                                <select name="estado" class="form-select @error('estado') is-invalid @enderror">
                                    <option value="" selected disabled>-- Elegir estado --</option>
                                    <option value="B" {{ old('estado') == 'B' ? 'selected' : ''}}>Esperando aprobación</option>
                                    <option value="P" {{ old('estado') == 'P' ? 'selected' : ''}}>Pendiente</option>
                                    <option value="R" {{ old('estado') == 'R' ? 'selected' : ''}}>Realizada</option>
                                    <option value="C" {{ old('estado') == 'C' ? 'selected' : ''}}>Cancelada</option>
                                </select>
                                @error('estado') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted text-uppercase">Operario Asignado</label>
                                <select name="operario_id" class="form-select @error('operario_id') is-invalid @enderror">
                                    <option value="">-- Sin asignar --</option>
                                    @foreach ($operarios as $operario)
                                    <option value="{{ $operario->id }}" {{ old('operario_id') == $operario->id ? 'selected' : '' }}>
                                        {{ $operario->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('operario_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted text-uppercase">Fecha Programada</label>
                                <input type="date" name="fecha_realizacion" class="form-control @error('fecha_realizacion') is-invalid @enderror" value="{{ old('fecha_realizacion') }}">
                                @error('fecha_realizacion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-bold text-muted text-uppercase">Anotaciones Privadas</label>
                                <textarea name="anotaciones" class="form-control @error('anotaciones') is-invalid @enderror" rows="2" placeholder="Notas internas o comentarios adicionales...">{{ old('anotaciones') }}</textarea>
                                @error('anotaciones') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                    </div>
                    <div class="card-footer bg-light p-4 border-0 text-end" style="border-radius: 0 0 15px 15px;">
                        <a href="{{ route('tareas.index') }}" class="btn btn-light border px-4 me-2">
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">
                            <i class="fas fa-save me-2"></i>Guardar Tarea
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection