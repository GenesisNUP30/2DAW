@extends('layouts.app')

@section('titulo', 'Editar cliente')

@section('content')

<div class="container py-4">

    {{-- Cabecera --}}
    <div class="row justify-content-center mb-4">
        <div class="col-lg-8">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h2 class="fw-bold m-0 text-dark">
                        <i class="fas fa-edit me-2"></i>Editar Cliente <span class="text-muted">ID: {{ $cliente->id }}</span>
                    </h2>
                    <p class="text-muted small mb-0">Actualiza la información de contacto, términos económicos o estado del cliente.</p>
                </div>
            </div>
        </div>
    </div>
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <form action="{{ route('clientes.update', $cliente) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                    <div class="card-body p-4">

                        {{-- SECCIÓN 1: Información del Cliente --}}
                        <h5 class="fw-bold mb-4 text-success border-bottom pb-2">
                            <i class="fas fa-id-card me-2"></i>Información del Cliente
                        </h5>

                        <div class="row g-3 mb-4">

                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase">CIF</label>
                                <input type="text" name="cif" class="form-control @error('cif') is-invalid @enderror"
                                    value="{{ old('cif', $cliente->cif) }}" placeholder="CIF del cliente">
                                @error('cif') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase">Nombre</label>
                                <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                                    value="{{ old('nombre', $cliente->nombre) }}" placeholder="Nombre del cliente">
                                @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase">Teléfono</label>
                                <input type="text" name="telefono" class="form-control @error('telefono') is-invalid @enderror"
                                    value="{{ old('telefono', $cliente->telefono) }}" placeholder="Teléfono del cliente">
                                @error('telefono') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase">Correo electrónico</label>
                                <input type="email" name="correo" class="form-control @error('correo') is-invalid @enderror"
                                    value="{{ old('correo', $cliente->correo) }}" placeholder="Correo electrónico del cliente">
                                @error('correo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- SECCIÓN 2: Datos bancarios de la cuota --}}
                        <h5 class="fw-bold mb-4 text-success border-bottom pb-2">
                            <i class="fas fa-coins me-2"></i>Datos bancarios de la Cuota
                        </h5>

                        <div class="row g-3 mb-4">
                            <div class="col-md-12">
                                <label class="form-label small fw-bold text-muted text-uppercase">Cuenta Bancaria</label>
                                <input type="text" name="cuenta_corriente" class="form-control @error('cuenta_corriente') is-invalid @enderror"
                                    value="{{ old('cuenta_corriente', $cliente->cuenta_corriente) }}" placeholder="Cuenta corriente del cliente">
                                @error('cuenta_corriente') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase">País</label>
                                <select name="pais" class="form-select @error('pais') is-invalid @enderror">
                                    <option value="" disabled>-- Selecciona un país --</option>
                                    @foreach ($paises as $pais)
                                    <option value="{{ $pais->iso2 }}" {{ old('pais', $cliente->pais) == $pais->iso2 ? 'selected' : '' }}>
                                        {{ $pais->nombre }} ({{ $pais->iso_moneda }})
                                    </option>
                                    @endforeach
                                </select>
                                @error('pais') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase">Importe de la cuota mensual</label>
                                <input type="number" step="0.01" name="importe_cuota_mensual" class="form-control @error('importe_cuota_mensual') is-invalid @enderror"
                                    value="{{ old('importe_cuota_mensual', $cliente->importe_cuota_mensual) }}" placeholder="Importe de la cuota mensual">
                                @error('importe_cuota_mensual') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- SECCIÓN 3: Datos de actividad--}}
                        <h5 class="fw-bold mb-4 text-success border-bottom pb-2">
                            <i class="fas fa-calendar-check me-2"></i>Registro en el sistema
                        </h5>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase">Fecha de alta</label>
                                <input type="date" name="fecha_alta" class="form-control @error('fecha_alta') is-invalid @enderror"
                                    value="{{ old('fecha_alta', $cliente->fecha_alta ? $cliente->fecha_alta->format('Y-m-d') : '') }}" placeholder="Fecha de alta">
                                @error('fecha_alta') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light p-4 border-0 text-end">
                        <a href="{{ route('clientes.index') }}" class="btn btn-light border px-4 me-2">
                            <i class="fas fa-times me-1"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-success px-5 fw-bold shadow-sm text-white">
                            <i class="fas fa-save me-2"></i>Actualizar Cliente
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection