@extends('layouts.app')

@section('titulo', 'Mi Perfil')

@section('content')
<div class="container">

    <h1 class="mb-4">
        <i class="fas fa-user me-2"></i> Mi Perfil
    </h1>

    {{-- MENSAJE DE ÉXITO --}}
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    {{-- ERRORES --}}
    @if ($errors->any())
    <div class="alert alert-danger">
        <strong>Por favor, corrige los siguientes errores:</strong>
    </div>
    @endif

    <!-- @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif -->


    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user-edit me-2"></i> Editar mis datos</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('perfil.update') }}">
                        @csrf
                        @method('PUT')

                        {{-- DNI --}}
                        <div class="mb-3">
                            <label class="form-label">DNI</label>
                            <input type="text" name="dni" class="form-control" value="{{ old('dni', $user->dni) }}">
                            @error('dni')
                            <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- NOMBRE --}}
                        <div class="mb-3">
                            <label class="form-label">Nombre *</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}">
                            @error('name')
                            <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- EMAIL --}}
                        <div class="mb-3">
                            <label class="form-label">Correo electrónico *</label>
                            <input type="text" name="email" class="form-control" value="{{ old('email', $user->email) }}">
                            @error('email')
                            <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- TELÉFONO --}}
                        <div class="mb-3">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="telefono" class="form-control" value="{{ old('telefono', $user->telefono) }}">
                            @error('telefono')
                            <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- DIRECCIÓN --}}
                        <div class="mb-3">
                            <label class="form-label">Dirección</label>
                            <input type="text" name="direccion" class="form-control" value="{{ old('direccion', $user->direccion) }}">
                            @error('direccion')
                            <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- FECHA DE ALTA --}}
                        <div class="mb-3">
                            <label class="form-label">Fecha de alta</label>
                            <input type="date" name="fecha_alta" class="form-control" value="{{ old('fecha_alta', $user->fecha_alta) }}" readonly>
                            @error('fecha_alta')
                            <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- CONTRASEÑA (opcional) --}}
                        @if ($user->isAdmin())
                        <div class="mb-3">
                            <label class="form-label">Nueva contraseña (dejar vacío para no cambiar)</label>
                            <input type="password" name="password" class="form-control">
                            @error('password')
                            <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- CONFIRMAR CONTRASEÑA --}}
                        <div class="mb-3">
                            <label class="form-label">Confirmar contraseña</label>
                            <input type="password" name="password_confirmation" class="form-control">
                            @error('password_confirmation')
                            <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        @endif

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-1"></i> Guardar cambios
                            </button>

                            <a href="{{ route('tareas.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- {{-- Información del usuario --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i> Información</h5>
                </div>
                <div class="card-body">
                    <p><strong><i class="fas fa-user-tag me-2"></i>Tipo:</strong>
                        @if ($user->isAdmin())
                        <span class="badge bg-danger">Administrador</span>
                        @else
                        <span class="badge bg-success">Operario</span>
                        @endif
                    </p>

                    <p><strong><i class="fas fa-calendar-check me-2"></i>Fecha de alta:</strong>
                        {{ \Carbon\Carbon::parse($user->fecha_alta)->format('d/m/Y') }}
                    </p>

                    <p><strong><i class="fas fa-user-clock me-2"></i>Último acceso:</strong>
                        {{ optional($user->updated_at)->format('d/m/Y H:i') ?? '-' }}
                    </p>
                </div>
            </div>
        </div> -->
    </div>

</div>
@endsection