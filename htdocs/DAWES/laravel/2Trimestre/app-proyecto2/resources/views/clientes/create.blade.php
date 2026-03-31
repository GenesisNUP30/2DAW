@extends('layouts.app')

@section('titulo', 'Añadir nuevo cliente')

@section('content')
<div class="container py-4">

    {{-- Cabecera --}}
    <div class="row justify-content-center mb-4">
        <div class="col-lg-10">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h2 class="fw-bold m-0 text-dark">
                        <i class="fas fa-user-plus me-2"></i>Añadir Nuevo Cliente
                    </h2>
                    <p class="text-muted small mb-0">Registra los datos de identificación y facturación del cliente.</p>
                </div>
                <a href="{{ route('clientes.index') }}" class="btn btn-sm btn-light border shadow-sm">
                    <i class="fas fa-arrow-left me-1"></i> Volver al listado
                </a>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <form action="{{ route('clientes.store') }}" method="POST">
                @csrf

                <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                    <div class="card-body p-4">

                        {{-- SECCIÓN 1: IDENTIFICACIÓN Y CONTACTO --}}
                        <h5 class="fw-bold mb-4 text-primary border-bottom pb-2">
                            <i class="fas fa-id-card me-2"></i>Identificación y Contacto
                        </h5>

                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted text-uppercase">CIF / NIF</label>
                                <input type="text" name="cif" class="form-control @error('cif') is-invalid @enderror"
                                    value="{{ old('cif') }}" placeholder="Ej: B12345678">
                                @error('cif') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-8">
                                <label class="form-label small fw-bold text-muted text-uppercase">Nombre o Razón Social</label>
                                <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                                    value="{{ old('nombre') }}" placeholder="Nombre completo de la empresa o profesional">
                                @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase">Correo electrónico</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-envelope small text-muted"></i></span>
                                    <input type="email" name="correo" class="form-control @error('correo') is-invalid @enderror"
                                        value="{{ old('correo') }}" placeholder="ejemplo@correo.com">
                                    @error('correo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase">Teléfono de contacto</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-phone-alt small text-muted"></i></span>
                                    <input type="text" name="telefono" class="form-control @error('telefono') is-invalid @enderror"
                                        value="{{ old('telefono') }}" placeholder="+34 000 000 000">
                                    @error('telefono') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label small fw-bold text-muted text-uppercase">País</label>
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

                        {{-- SECCIÓN 2: DATOS DE FACTURACIÓN --}}
                        <h5 class="fw-bold mb-4 text-primary border-bottom pb-2">
                            <i class="fas fa-file-invoice-dollar me-2"></i>Configuración de Facturación
                        </h5>

                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label small fw-bold text-muted text-uppercase">Cuenta Corriente (IBAN)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-university small text-muted"></i></span>
                                    <input type="text" name="cuenta_corriente" class="form-control @error('cuenta_corriente') is-invalid @enderror"
                                        value="{{ old('cuenta_corriente') }}" placeholder="ES00 0000 0000...">
                                    @error('cuenta_corriente') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted text-uppercase">Cuota Mensual</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" name="importe_cuota_mensual" class="form-control fw-bold @error('importe_cuota_mensual') is-invalid @enderror"
                                        value="{{ old('importe_cuota_mensual') }}" placeholder="0.00">
                                    <span class="input-group-text bg-light text-muted small">€/mes</span>
                                    @error('importe_cuota_mensual') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="p-3 bg-light rounded border-start border-primary border-4 mt-2">
                                    <p class="small text-muted mb-0">
                                        <i class="fas fa-info-circle me-1"></i> El importe de la cuota se utilizará para la generación automática de las remesas mensuales.
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Footer de Acción --}}
                    <div class="card-footer bg-light p-4 border-0 text-end" style="border-radius: 0 0 15px 15px;">
                        <a href="{{ route('clientes.index') }}" class="btn btn-light border px-4 me-2">
                            <i class="fas fa-times me-1"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">
                            <i class="fas fa-save me-2"></i>Registrar Cliente
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection