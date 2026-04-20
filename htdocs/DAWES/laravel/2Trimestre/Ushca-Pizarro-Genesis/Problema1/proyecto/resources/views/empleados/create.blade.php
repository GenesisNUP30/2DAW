@extends('layouts.app')

@section('titulo', 'Añadir empleado')

@section('content')
<div class="container py-4">

    {{-- Cabecera --}}
    <div class="row justify-content-center mb-4">
        <div class="col-lg-10">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h2 class="fw-bold m-0 text-dark">
                        <i class="fas fa-user-plus me-2"></i>Alta de Empleado
                    </h2>
                    <p class="text-muted small mb-0">Configura el perfil y los permisos de acceso para un nuevo miembro del equipo.</p>
                </div>
                <a href="{{ route('empleados.index') }}" class="btn btn-sm btn-light border shadow-sm">
                    <i class="fas fa-arrow-left me-1"></i> Volver al listado
                </a>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <form method="POST" action="{{ route('empleados.store') }}">
                @csrf

                <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                    <div class="card-body p-4">
                        
                        {{-- SECCIÓN 1: DATOS PERSONALES --}}
                        <h5 class="fw-bold mb-4 text-primary border-bottom pb-2">
                            <i class="fas fa-id-card me-2"></i>Información Personal
                        </h5>
                        
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted text-uppercase">DNI / NIE</label>
                                <input type="text" name="dni" class="form-control @error('dni') is-invalid @enderror" 
                                       value="{{ old('dni') }}" placeholder="12345678X">
                                @error('dni') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-8">
                                <label class="form-label small fw-bold text-muted text-uppercase">Nombre Completo</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name') }}" placeholder="Ej: Juan Pérez García">
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label small fw-bold text-muted text-uppercase">Dirección de Residencia</label>
                                <input type="text" name="direccion" class="form-control @error('direccion') is-invalid @enderror" 
                                       value="{{ old('direccion') }}" placeholder="Calle, número, piso...">
                                @error('direccion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- SECCIÓN 2: CONTACTO Y EMPRESA --}}
                        <h5 class="fw-bold mb-4 text-primary border-bottom pb-2">
                            <i class="fas fa-briefcase me-2"></i>Contacto y Empresa
                        </h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase">Correo Electrónico Corporativo</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-envelope text-muted"></i></span>
                                    <input type="text" name="email" class="form-control @error('email') is-invalid @enderror" 
                                           value="{{ old('email') }}" placeholder="usuario@empresa.com">
                                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase">Teléfono</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-phone text-muted"></i></span>
                                    <input type="text" name="telefono" class="form-control @error('telefono') is-invalid @enderror" 
                                           value="{{ old('telefono') }}">
                                    @error('telefono') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase">Fecha de Alta</label>
                                <input type="date" name="fecha_alta" class="form-control @error('fecha_alta') is-invalid @enderror" 
                                       value="{{ old('fecha_alta', date('Y-m-d')) }}">
                                @error('fecha_alta') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase">Rol de Usuario</label>
                                <select name="tipo" class="form-select @error('tipo') is-invalid @enderror">
                                    <option value="" selected disabled>-- Selecciona tipo --</option>
                                    <option value="administrador" {{ old('tipo') == 'administrador' ? 'selected' : '' }}>Administrador</option>
                                    <option value="operario" {{ old('tipo') == 'operario' ? 'selected' : '' }}>Operario</option>
                                </select>
                                @error('tipo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- SECCIÓN 3: SEGURIDAD --}}
                        <h5 class="fw-bold mb-4 text-primary border-bottom pb-2">
                            <i class="fas fa-key me-2"></i>Seguridad y Acceso
                        </h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase">Contraseña de acceso</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase">Confirmar contraseña</label>
                                <input type="password" name="password_confirmation" class="form-control">
                            </div>
                            <div class="col-12">
                                <div class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i> La contraseña debe tener al menos 8 caracteres.
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer bg-light p-4 border-0 text-end" style="border-radius: 0 0 15px 15px;">
                        <a href="{{ route('empleados.index') }}" class="btn btn-light border px-4 me-2">
                           <i class="fas fa-times me-1"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">
                            <i class="fas fa-save me-2"></i>Registrar Empleado
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection