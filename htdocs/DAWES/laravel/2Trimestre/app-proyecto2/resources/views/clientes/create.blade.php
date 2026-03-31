@extends('layouts.app')

@section('titulo', 'Añadir nuevo cliente')

@section('content')
<div class="container-fluid px-4 py-3">

    {{-- Cabecera --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold m-0 text-dark">
            <i class="fas fa-user-plus me-2"></i>Añadir nuevo cliente
        </h2>
        <a href="{{ route('clientes.index') }}" class="btn btn-sm btn-light border">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
    </div>

    {{-- Alerta de Errores Global --}}
    @if ($errors->any())
    <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-radius: 12px;">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-circle me-3 fa-lg"></i>
            <div>
                <span class="fw-bold">Se encontraron algunos problemas.</span>
                Por favor, revisa los campos marcados en rojo a continuación.
            </div>
        </div>
    </div>
    @endif

    <form action="{{ route('clientes.store') }}" method="POST">
        @csrf

        <div class="row g-4">
            {{-- Columna: Información Básica --}}
            <div class="col-lg-7">
                <div class="card border shadow-sm h-100" style="border-radius: 12px;">
                    <div class="card-header bg-white py-3">
                        <h5 class="card-title mb-0 fw-bold" style="font-size: 1rem;">
                            <i class="fas fa-id-card me-2 text-muted"></i>Identificación y Contacto
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label small fw-bold">CIF / NIF</label>
                                <input type="text" name="cif" class="form-control @error('cif') is-invalid @enderror"
                                    value="{{ old('cif') }}" placeholder="Ej: B12345678">
                                @error('cif') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-8">
                                <label class="form-label small fw-bold">Nombre o Razón Social</label>
                                <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                                    value="{{ old('nombre') }}" placeholder="Nombre completo de la empresa">
                                @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Correo electrónico</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted"><i class="fas fa-envelope"></i></span>
                                    <input type="email" name="correo" class="form-control @error('correo') is-invalid @enderror"
                                        value="{{ old('correo') }}" placeholder="ejemplo@correo.com">
                                </div>
                                @error('correo') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Teléfono de contacto</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted"><i class="fas fa-phone"></i></span>
                                    <input type="text" name="telefono" class="form-control @error('telefono') is-invalid @enderror"
                                        value="{{ old('telefono') }}" placeholder="+34 000 000 000">
                                </div>
                                @error('telefono') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-bold">País</label>
                                <select name="pais" class="form-select @error('pais') is-invalid @enderror">
                                    <option value="" selected disabled>Selecciona un país...</option>
                                    @foreach ($paises as $pais)
                                    <option value="{{ $pais->iso2 }}" {{ old('pais') === $pais->iso2 ? 'selected' : '' }}>
                                        {{ $pais->nombre }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('pais') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Columna: Datos de Facturación --}}
            <div class="col-lg-5">
                <div class="card border shadow-sm" style="border-radius: 12px;">
                    <div class="card-header bg-white py-3">
                        <h5 class="card-title mb-0 fw-bold" style="font-size: 1rem;">
                            <i class="fas fa-file-invoice-dollar me-2 text-muted"></i>Datos de Facturación
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label class="form-label small fw-bold">Cuenta Corriente (IBAN)</label>
                            <input type="text" name="cuenta_corriente" class="form-control @error('cuenta_corriente') is-invalid @enderror"
                                value="{{ old('cuenta_corriente') }}" placeholder="ES00 0000 0000...">
                            @error('cuenta_corriente') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold">Importe Cuota Mensual</label>
                            <div class="input-group">
                                <input type="text" step="0.01" name="importe_cuota_mensual" class="form-control fw-bold @error('importe_cuota_mensual') is-invalid @enderror"
                                    value="{{ old('importe_cuota_mensual') }}" placeholder="0.00">
                                <span class="input-group-text bg-white">€ / mes</span>
                            </div>
                            <div class="form-text mt-2 small text-muted">
                                <i class="fas fa-info-circle me-1"></i> Este importe se utilizará para la generación automática de cuotas mensuales. Si va a introducir decimales, use el punto (.) como separador.
                            </div>
                            @error('importe_cuota_mensual')
                            <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4 opacity-50">

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary py-2 fw-bold shadow-sm">
                                <i class="fas fa-save me-2"></i>Registrar cliente
                            </button>
                            <a href="{{ route('clientes.index') }}" class="btn btn-light border py-2 text-muted">
                                <i class="fas fa-times me-1"></i> Cancelar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection