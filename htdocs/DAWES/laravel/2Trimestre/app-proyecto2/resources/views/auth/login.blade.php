@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- Mensaje de éxito --}}
    @if (session('success'))
    <div class="alert alert-success shadow-sm mb-4">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
    </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm" style="border-radius: 15px; overflow: hidden;">
                <div class="card-header bg-white pt-3 pb-1 text-center">
                    <h4 class="fw-bold text-primary">{{ __('Iniciar Sesión') }}</h4>
                    <p class="text-muted small">Accede al panel de gestión de tareas</p>
                </div>

                <div class="card-body px-4 py-3">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-2">
                            <label for="email" class="form-label small fw-bold text-muted text-uppercase">Correo Electrónico</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-envelope text-muted"></i>
                                </span>
                                <input id="email" type="email"
                                    class="form-control border-start-0 @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}"
                                    placeholder="ejemplo@correo.com"
                                    required autocomplete="email" autofocus>

                                @error('email')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label small fw-bold text-muted text-uppercase">Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-lock text-muted"></i>
                                </span>
                                <input id="password" type="password"
                                    class="form-control border-start-0 @error('password') is-invalid @enderror"
                                    name="password"
                                    required autocomplete="current-password">

                                @error('password')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label small" for="remember">
                                        {{ __('Recordarme') }}
                                    </label>
                                </div>
                            </div>
                            <div class="col-6 text-end">
                                @if (Route::has('password.request'))
                                <a class="small text-decoration-none" href="{{ route('password.request') }}">
                                    {{ __('¿Olvidaste tu clave?') }}
                                </a>
                                @endif
                            </div>
                        </div>

                        <div class="text-center mb-0">
                            <button type="submit" class="btn btn-primary fw-bold py-2 shadow-sm">
                                <i class="fas fa-sign-in-alt me-2"></i>{{ __('Entrar') }}
                            </button>
                        </div>
                    </form>

                    {{-- SECCIÓN PARA CLIENTES --}}
                    <div class="mt-3 pt-3 border-top text-center">
                        <p class="text-muted small mb-3">¿Eres un cliente y necesitas reportar una incidencia?</p>
                        <a href="{{ route('incidencia.create') }}" class="btn btn-outline-success btn-sm px-4">
                            <i class="fas fa-tools me-2"></i>Registrar Incidencia
                        </a>
                    </div>
                </div>
            </div>

            <!-- @if (Route::has('register'))
            <div class="text-center mt-3">
                <p class="text-muted small">¿No tienes cuenta? <a href="{{ route('register') }}" class="fw-bold text-decoration-none">Regístrate aquí</a></p>
            </div> -->
            @endif
        </div>
    </div>
</div>
@endsection