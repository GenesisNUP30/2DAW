@extends('layouts.app')

@section('titulo', 'Gestión de Factura')

@section('content')
<div class="container-fluid px-4">

<div class="row">
        <div class="col-12">
            <h1 class="my-4"><i class="fa-solid fa-file-invoice"></i> Gestión de Factura</h1>
        </div>
    </div>

    <div class="row">
        {{-- Columna Principal (Izquierda) --}}
        <div class="col-lg-8">

            {{-- Alertas de sesión --}}
            @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
            @endif
            @if (session('error'))
            <div class="alert alert-danger border-0 shadow-sm">{{ session('error') }}</div>
            @endif

            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <span>Detalles de la Cuota</span>
                    <span class="badge bg-secondary">Nº {{ $cuota->id }}</span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted small uppercase">Cliente</div>
                        <div class="col-sm-8 fw-bold">{{ $cuota->cliente->nombre }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted small">Concepto</div>
                        <div class="col-sm-8">{{ $cuota->concepto }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted small">Importe Total</div>
                        <div class="col-sm-8 h5 text-primary">
                            {{ number_format($cuota->importe,2,',','.') }} {{ $cuota->cliente->moneda }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted small">Fecha de Emisión</div>
                        <div class="col-sm-8">{{ $cuota->fecha_emision->format('d/m/Y') }}</div>
                    </div>

                    @php $factura = \App\Models\Factura::where('cuota_id', $cuota->id)->first(); @endphp

                    <div class="d-flex gap-2 mt-4">
                        @if(!$factura)
                        {{-- Si no existe factura, botón para crearla --}}
                        <form action="{{ route('facturas.generar', $cuota->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-gear me-2"></i> Generar Factura y PDF
                            </button>
                        </form>
                        @else
                        {{-- Si ya existe, botones de descargar y enviar --}}
                        

                        <a href="{{ route('facturas.descargar', $factura->id) }}" class="btn btn-outline-secondary">
                            <i class="fa-solid fa-download me-2"></i> Descargar PDF
                        </a>

                        <form action="{{ route('facturas.enviar', $factura->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success px-4">
                                <i class="fa-solid fa-paper-plane me-2"></i> Enviar por Email
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Columna de Acompañamiento (Derecha) --}}
        <div class="col-lg-4 mt-4 mt-lg-0">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body">
                    <h5 class="card-title text-dark fw-bold mb-3"><i class="fa-solid fa-circle-info me-2"></i> Estado y Ayuda</h5>
                    <p class="small text-muted mb-4">
                        Aquí puedes gestionar el flujo de facturación. Una vez generada la factura, el sistema registrará el número correlativo y permitirá el envío automático por correo electrónico.
                    </p>

                    @if($factura)
                    <div class="p-3" style="background-color: #f0f2f5;">
                        <div class="mb-2">
                            <small class="d-block text-muted">Factura No:</small>
                            <span class="fw-bold text-dark">{{ $factura->numero_factura }}</span>
                        </div>
                        <div class="list-group-item bg-transparent px-0">
                            <small class="d-block text-muted">Estado:</small>
                            <span class="badge bg-success-soft text-success border border-success">
                                <i class="fa-solid fa-check-circle me-1"></i> Generada
                            </span>
                        </div>
                    </div>
                    @else
                    <div class="alert alert-warning border-0 small mb-0">
                        <i class="fa-solid fa-triangle-exclamation me-1"></i>
                        Aún no se ha generado un documento legal para esta cuota.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection