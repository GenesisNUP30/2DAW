@extends('layouts.app')

@section('titulo', 'Registrar Incidencia')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h1 class="mb-4 text-success">
                <i class="fas fa-clipboard-list me-2"></i> Nueva Incidencia de Cliente
            </h1>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <i class="fas fa-user-check me-2"></i> Verificación de Identidad
                </div>
                <div class="card-body bg-light">
                    <p class="small text-muted">Para garantizar su identidad, introduzca sus datos de registro:</p>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">CIF del Cliente</label>
                            <input type="text" name="cif" form="form-incidencia" class="form-control" value="{{ old('cif') }}" placeholder="Ej: B12345678">
                            @error('identidad') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Teléfono de Registro</label>
                            <input type="text" name="telefono_cliente" form="form-incidencia" class="form-control" value="{{ old('telefono_cliente') }}" placeholder="Teléfono con el que se registró">
                        </div>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('incidencia.store') }}" id="form-incidencia">
                @csrf

                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <i class="fas fa-info-circle me-2"></i> Datos de la Incidencia
                    </div>
                    <div class="card-body">
                        <div class="row">
                            {{-- CONTACTO --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nombre de contacto</label>
                                <input type="text" name="persona_contacto" class="form-control" value="{{ old('persona_contacto') }}">
                                @error('persona_contacto') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Teléfono de contacto para esta avería</label>
                                <input type="text" name="telefono_contacto" class="form-control" value="{{ old('telefono_contacto') }}">
                                @error('telefono_contacto') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            {{-- DESCRIPCIÓN --}}
                            <div class="col-12 mb-3">
                                <label class="form-label">Descripción de la avería</label>
                                <textarea name="description" class="form-control" rows="3">{{ old('descripcion') }}</textarea>
                                @error('descripcion') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            {{-- CORREO Y DIRECCIÓN --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Correo electrónico</label>
                                <input type="email" name="correo_contacto" class="form-control" value="{{ old('correo_contacto') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Dirección exacta</label>
                                <input type="text" name="direccion" class="form-control" value="{{ old('direccion') }}">
                            </div>

                            {{-- UBICACIÓN --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Población</label>
                                <input type="text" name="poblacion" class="form-control" value="{{ old('poblacion') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Código postal</label>
                                <input type="text" name="codigo_postal" class="form-control" value="{{ old('codigo_postal') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Provincia</label>
                                <select name="provincia" class="form-select">
                                    <option value="">-- Seleccione --</option>
                                    @foreach ($provincias as $codigo => $nombre)
                                        <option value="{{ $codigo }}" {{ old('provincia') == $codigo ? 'selected' : '' }}>{{ $nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- FECHA --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Fecha preferente de realización</label>
                                <input type="date" name="fecha_realizacion" class="form-control" value="{{ old('fecha_realizacion') }}">
                            </div>

                            {{-- ANOTACIONES --}}
                            <div class="col-12 mb-4">
                                <label class="form-label">Anotaciones adicionales</label>
                                <textarea name="anotaciones_anteriores" class="form-control" rows="2">{{ old('anotaciones_anteriores') }}</textarea>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('login') }}" class="btn btn-secondary">Volver al Login</a>
                            <button type="submit" class="btn btn-success px-5">
                                <i class="fas fa-paper-plane me-2"></i>Enviar Incidencia
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection