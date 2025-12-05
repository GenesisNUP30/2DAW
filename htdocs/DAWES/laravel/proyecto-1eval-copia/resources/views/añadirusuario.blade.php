@extends('layouts.plantilla01')

@section('titulo', 'Añadir Usuario')

@section('estilos')
<style>
    .error {
        color: #e53e3e;
        font-size: 0.875rem;
        margin-top: 0.25rem;
        display: block;
    }

    .form-label {
        font-weight: 600;
        margin-top: 1.25rem;
        color: #2d3748;
        display: block;
    }

    .form-control, .form-select {
        border: 1px solid #cbd5e0;
        border-radius: 6px;
        padding: 0.5rem 0.75rem;
        font-size: 1rem;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15);
        outline: none;
    }

    .btn-submit {
        background: linear-gradient(135deg, #4299e1, #3182ce);
        color: white;
        border: none;
        padding: 0.6rem 1.5rem;
        font-size: 1rem;
        font-weight: 600;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-submit:hover {
        background: linear-gradient(135deg, #3182ce, #2b6cb0);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(66, 153, 225, 0.3);
    }

    .btn-cancel {
        background: linear-gradient(135deg, #718096, #4a5568);
        color: white;
        text-decoration: none;
        padding: 0.6rem 1.5rem;
        font-size: 1rem;
        font-weight: 600;
        border-radius: 6px;
        display: inline-block;
        transition: all 0.2s ease;
        margin-left: 0.75rem;
    }

    .btn-cancel:hover {
        background: linear-gradient(135deg, #4a5568, #2d3748);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(71, 80, 96, 0.3);
    }

    .form-row {
        margin-bottom: 1.25rem;
    }
</style>
@endsection

@section('cuerpo')
<h1 class="mb-4">
    <i class="fas fa-user-plus me-2"></i>Añadir Usuario
</h1>

<form method="POST" action="{{ miurl('añadirusuario') }}">
    <div class="form-row">
        <label class="form-label">Nombre de usuario</label>
        <input type="text" name="usuario" class="form-control" value="{{ $usuario ?? '' }}">
        {!! \App\Models\Funciones::verErrores('usuario') !!}
    </div>

    <div class="form-row">
        <label class="form-label">Contraseña</label>
        <input type="password" name="password" class="form-control">
        {!! \App\Models\Funciones::verErrores('password') !!}
    </div>

    <div class="form-row">
        <label class="form-label">Confirmar contraseña</label>
        <input type="password" name="password2" class="form-control">
        {!! \App\Models\Funciones::verErrores('password2') !!}
    </div>

    <div class="form-row">
        <label class="form-label">Rol</label>
        <select name="rol" class="form-select">
            <option value="">Seleccione un rol</option>
            <option value="administrador" @if(($rol ?? '')=='administrador') selected @endif>Administrador</option>
            <option value="operario" @if(($rol ?? '')=='operario') selected @endif>Operario</option>
        </select>
        {!! \App\Models\Funciones::verErrores('rol') !!}
    </div>

    <div class="mt-4">
        <button type="submit" class="btn-submit">
            <i class="fas fa-user-plus me-1"></i>Crear usuario
        </button>
        <a href="{{ miurl('/') }}" class="btn-cancel">
            <i class="fas fa-times me-1"></i>Cancelar
        </a>
    </div>
</form>
@endsection