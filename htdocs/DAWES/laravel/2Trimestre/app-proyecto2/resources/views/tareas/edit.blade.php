@extends('layouts.app')

@section('titulo', 'Editar tarea')

@section('content')
<div class="container">

    <h1 class="mb-4">
        <i class="fas fa-edit me-2"></i> Editar tarea ID: {{ $tarea->id }}
    </h1>

    {{-- ERRORES --}}
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('tareas.update', $tarea) }}" method="POST">
        @csrf

        {{-- CLIENTE --}}
        <div class="mb-3">
            <label class="form-label">Cliente</label>
            <select name="cliente_id" class="form-select" required>
                <option value="">-- Selecciona cliente --</option>
                @foreach ($clientes as $cliente)
                <option value="{{ $cliente->id }}"
                    {{ old('cliente_id', $tarea->cliente_id) == $cliente->id ? 'selected' : '' }}>
                    {{ $cliente->nombre }} ({{ $cliente->cif }})
                </option>
                @endforeach
            </select>
        </div>

        {{-- PERSONA DE CONTACTO --}}
        <div class="mb-3">
            <label class="form-label">Persona de contacto</label>
            <input type="text" name="persona_contacto" class="form-control" value="{{ old('persona_contacto', $tarea->persona_contacto) }}" required>
        </div>

        {{-- TELÉFONO --}}
        <div class="mb-3">
            <label class="form-label">Teléfono de contacto</label>
            <input type="text" name="telefono_contacto" class="form-control" value="{{ old('telefono_contacto', $tarea->telefono_contacto) }}" required>
        </div>

        {{-- DESCRIPCIÓN --}}
        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control" rows="4" required>{{ old('descripcion', $tarea->descripcion) }}</textarea>
        </div>

        {{-- CORREO --}}
        <div class="mb-3">
            <label class="form-label">Correo electrónico</label>
            <input type="email" name="correo_contacto" class="form-control" value="{{ old('correo_contacto', $tarea->correo_contacto) }}" required>
        </div>

        {{-- DIRECCIÓN --}}
        <div class="mb-3">
            <label class="form-label">Dirección</label>
            <input type="text" name="direccion" class="form-control" value="{{ old('direccion') }}">
        </div>

        <div class="row">
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
                <select name="provincia" class="form-select" required>
                    <option value="">-- Selecciona provincia --</option>
                    @foreach ($provincias as $codigo => $nombre)
                    <option value="{{ $codigo }}"
                        {{ old('provincia') == $codigo ? 'selected' : '' }}>
                        {{ $nombre }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- ESTADO --}}
        <div class="mb-3">
            <label class="form-label">Estado</label>
            <select name="estado" class="form-select">
                <option value="">-- Elija un estado --</option>
                <option value="B" {{ old('B') == 'B' ? 'selected' : ''}}>Esperando a ser aprobada</option>
                <option value="P" {{ old('P') == 'P' ? 'selected' : ''}}>Pendiente</option>
                <option value="R" {{ old('R') == 'R' ? 'selected' : ''}}>Realizada</option>
                <option value="C" {{ old('C') == 'C' ? 'selected' : ''}}>Completada</option>
            </select>

        {{-- OPERARIO --}}
        <div class="mb-3">
            <label class="form-label">Operario asignado</label>
            <select name="operario_id" class="form-select">
                <option value="">-- Sin asignar --</option>
                @foreach ($operarios as $operario)
                <option value="{{ $operario->id }}"
                    {{ old('operario_id') == $operario->id ? 'selected' : '' }}>
                    {{ $operario->nombre }}
                </option>
                @endforeach
            </select>
        </div>

                
        {{-- FECHA --}}
        <div class="mb-3">
            <label class="form-label">Fecha de realización</label>
            <input type="date" name="fecha_realizacion" class="form-control" value="{{ old('fecha_realizacion') }}">
        </div>

        {{-- ANOTACIONES --}}
        <div class="mb-3">
            <label class="form-label">Anotaciones</label>
            <textarea name="anotaciones" class="form-control" rows="4">{{ old('anotaciones') }}</textarea>
        </div>

        {{-- BOTONES --}}
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success">
                Actualizar tarea
            </button>

            <a href="{{ route('tareas.index') }}" class="btn btn-secondary">
                Cancelar
            </a>
        </div>
    </form>

</div>
@endsection