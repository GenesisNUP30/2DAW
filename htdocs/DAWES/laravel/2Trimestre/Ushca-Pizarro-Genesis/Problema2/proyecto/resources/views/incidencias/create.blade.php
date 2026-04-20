@extends('layouts.app')

@section('titulo', 'Registrar Incidencia')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            {{-- Cabecera de Página --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold m-0 text-dark">
                        <i class="fas fa-clipboard-list text-success me-2"></i>Nueva Incidencia
                    </h2>
                    <p class="text-muted m-0">Por favor, rellene los datos para solicitar asistencia técnica.</p>
                </div>
                <a href="{{ route('login') }}" class="btn btn-light border px-3">
                    <i class="fas fa-arrow-left me-1"></i> Volver al Login
                </a>
            </div>

            {{-- SECCIÓN 1: Verificación de Identidad --}}
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3 text-success">
                        <i class="fas fa-user-check me-2"></i>1. Verificación de Cliente
                    </h5>
                    <p class="small text-muted mb-4">Introduzca sus datos de registro para validar la solicitud.</p>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase">CIF del Cliente</label>
                            <input type="text" name="cif" form="form-incidencia" 
                                   class="form-control @error('cif') is-invalid @enderror" 
                                   value="{{ old('cif') }}" placeholder="Ej: B12345678">
                            @error('cif') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase">Teléfono de Registro</label>
                            <input type="text" name="telefono_cliente" form="form-incidencia" 
                                   class="form-control @error('telefono_cliente') is-invalid @enderror" 
                                   value="{{ old('telefono_cliente') }}" placeholder="Teléfono asociado a su cuenta">
                            @error('telefono_cliente') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECCIÓN 2: Datos de la Incidencia --}}
            <form method="POST" action="{{ route('incidencia.store') }}" id="form-incidencia">
                @csrf
                <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4 text-primary">
                            <i class="fas fa-info-circle me-2"></i>2. Detalles 
                        </h5>

                        <div class="row g-3">
                            {{-- CONTACTO --}}
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase">Persona de contacto</label>
                                <input type="text" name="persona_contacto" class="form-control @error('persona_contacto') is-invalid @enderror" 
                                       value="{{ old('persona_contacto') }}" placeholder="Nombre del responsable">
                                @error('persona_contacto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase">Teléfono de contacto</label>
                                <input type="text" name="telefono_contacto" class="form-control @error('telefono_contacto') is-invalid @enderror" 
                                       value="{{ old('telefono_contacto') }}" placeholder="Ej: 666 555 444">
                                @error('telefono_contacto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- DESCRIPCIÓN --}}
                            <div class="col-12 mt-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Descripción de la incidencia</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                          rows="3" placeholder="Describa brevemente qué sucede..."></textarea>
                                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- CORREO Y DIRECCIÓN --}}
                            <div class="col-md-6 mt-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Correo electrónico de contacto</label>
                                <input type="email" name="correo_contacto" class="form-control @error('correo_contacto') is-invalid @enderror" 
                                       value="{{ old('correo_contacto') }}">
                                @error('correo_contacto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mt-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Dirección (Calle, número)</label>
                                <input type="text" name="direccion" class="form-control @error('direccion') is-invalid @enderror" 
                                       value="{{ old('direccion') }}">
                                @error('direccion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- UBICACIÓN --}}
                            <div class="col-md-4 mt-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Población</label>
                                <input type="text" name="poblacion" class="form-control @error('poblacion') is-invalid @enderror" 
                                       value="{{ old('poblacion') }}">
                                @error('poblacion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4 mt-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Código Postal</label>
                                <input type="text" name="codigo_postal" class="form-control @error('codigo_postal') is-invalid @enderror" 
                                       value="{{ old('codigo_postal') }}">
                                @error('codigo_postal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4 mt-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Provincia</label>
                                <select name="provincia" class="form-select @error('provincia') is-invalid @enderror">
                                    <option value="" selected disabled>-- Seleccione --</option>
                                    @foreach ($provincias as $codigo => $nombre)
                                        <option value="{{ $codigo }}" {{ old('provincia') == $codigo ? 'selected' : '' }}>{{ $nombre }}</option>
                                    @endforeach
                                </select>
                                @error('provincia') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- ESTADO Y FECHA --}}
                            <div class="col-md-6 mt-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Estado inicial</label>
                                <select name="estado" class="form-select @error('estado') is-invalid @enderror">
                                    <option value="" selected disabled>-- Elegir estado --</option>
                                    <option value="B" {{ old('estado') == 'B' ? 'selected' : ''}}>Esperando aprobación</option>
                                    <option value="P" {{ old('estado') == 'P' ? 'selected' : ''}}>Pendiente</option>
                                </select>
                                @error('estado') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mt-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Fecha de realización preferente</label>
                                <input type="date" name="fecha_realizacion" class="form-control @error('fecha_realizacion') is-invalid @enderror" 
                                       value="{{ old('fecha_realizacion') }}">
                                @error('fecha_realizacion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- ANOTACIONES --}}
                            <div class="col-12 mt-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Anotaciones adicionales</label>
                                <textarea name="anotaciones_anteriores" class="form-control" rows="2"></textarea>
                            </div>
                        </div>

                        <div class="mt-4 pt-4 border-top">
                            <button type="submit" class="btn btn-success fw-bold px-5 py-2 shadow-sm w-100 w-md-auto float-md-end">
                                <i class="fas fa-paper-plane me-2"></i>Enviar Solicitud
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection