@extends('layouts.app')

@section('titulo', 'Mi Perfil')

@section('content')
<div class="container py-4">

    {{-- Cabecera de Página --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold m-0 text-dark">
                <i class="fas fa-user-circle me-2"></i>Mi Perfil
            </h2>
            <p class="text-muted m-0">Gestiona tu información personal y credenciales de acceso.</p>
        </div>
        <a href="{{ route('home') }}" class="btn btn-light border px-3">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
    </div>

    {{-- Notificaciones --}}
    @if (session('success'))
    <div class="alert alert-success border-0 shadow-sm mb-4">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
    </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('perfil.update') }}">
                        @csrf
                        @method('PUT')

                        <h5 class="fw-bold mb-4 pb-2 border-bottom text-primary">
                            <i class="fas fa-id-card me-2"></i>Información Personal
                        </h5>

                        <div class="row g-3">
                            {{-- DNI --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">DNI</label>
                                <input type="text" name="dni" class="form-control @error('dni') is-invalid @enderror"
                                    value="{{ old('dni', $user->dni) }}" placeholder="00000000X">
                                @error('dni') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- NOMBRE --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Nombre Completo *</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $user->name) }}">
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- EMAIL --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Correo Electrónico *</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $user->email) }}">
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- TELÉFONO --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Teléfono</label>
                                <input type="text" name="telefono" class="form-control @error('telefono') is-invalid @enderror"
                                    value="{{ old('telefono', $user->telefono) }}">
                                @error('telefono') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- DIRECCIÓN --}}
                            <div class="col-md-8 mb-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Dirección Residencia</label>
                                <input type="text" name="direccion" class="form-control @error('direccion') is-invalid @enderror"
                                    value="{{ old('direccion', $user->direccion) }}">
                                @error('direccion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- FECHA DE ALTA (Solo lectura) --}}
                            <div class="col-md-4 mb-4">
                                <label class="form-label small fw-bold text-muted text-uppercase">Fecha de registro</label>
                                <input type="text" class="form-control bg-light border-0"
                                    value="{{ \Carbon\Carbon::parse($user->fecha_alta)->format('d/m/Y') }}" readonly>
                            </div>
                        </div>

                        {{-- SECCIÓN CONTRASEÑA --}}
                        @if ($user->isAdmin())
                        <h5 class="fw-bold mt-4 mb-4 pb-2 border-bottom text-primary">
                            <i class="fas fa-lock me-2"></i>Seguridad y Acceso
                        </h5>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase">Nueva Contraseña</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Dejar vacío para mantener actual">
                                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase">Confirmar Contraseña</label>
                                <input type="password" name="password_confirmation" class="form-control">
                            </div>
                        </div>
                        @endif

                        <div class="d-flex gap-2 pt-4 border-top">
                            <button type="submit" class="btn btn-success px-4 fw-bold shadow-sm">
                                <i class="fas fa-save me-2"></i>Actualizar Perfil
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Widget lateral (Resumen rápido) --}}
        <div class="col-lg-4 mt-4 mt-lg-0">
            <div class="card border-0 shadow-sm text-center p-4" style="border-radius: 12px; background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);">
                <div class="mb-3">
                    {{-- Avatar con inicial --}}
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center shadow" style="width: 80px; height: 80px;">
                        <span class="h2 m-0">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                    </div>
                </div>
                <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                <div class="mb-3">
                    <span class="badge rounded-pill d-inline-block px-3 py-2 {{ $user->isAdmin() ? 'bg-primary' : 'bg-success' }}" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                        <i class="fas {{ $user->isAdmin() ? 'fa-user-shield' : 'fa-hard-hat' }} me-1"></i>
                        {{ $user->isAdmin() ? 'ADMINISTRADOR' : 'OPERARIO' }}
                    </span>
                </div>
                <hr class="my-4 opacity-50">
                <div class="text-start">
                    <p class="small text-muted mb-1"><i class="fas fa-envelope me-2"></i> Correo: <span class="text-dark">{{ $user->email }}</span></p>
                    <p class="small text-muted mb-0"><i class="fas fa-calendar-alt me-2"></i> Miembro desde: <span class="text-dark">{{ \Carbon\Carbon::parse($user->fecha_alta)->format('d/m/Y') }}</span></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection