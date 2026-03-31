@extends('layouts.app')

@section('titulo', 'Gestión de Factura')

@section('content')
<div class="container-fluid px-4 py-3">

    {{-- Cabecera con botón de retorno --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold m-0 text-dark">
                <i class="fa-solid fa-file-invoice me-2"></i>Gestión de Factura
            </h2>
            <p class="text-muted small mb-0">Control de emisión, descarga y envío de documentos fiscales.</p>
        </div>
        <a href="{{ route('cuotas.index') }}" class="btn btn-sm btn-light border shadow-sm">
            <i class="fa-solid fa-arrow-left me-1"></i> Volver a Cuotas
        </a>
    </div>

    <div class="row">
        {{-- Columna Principal: Detalles y Acciones --}}
        <div class="col-lg-8">

            {{-- Alertas de Sistema --}}
            @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4 d-flex align-items-center">
                <i class="fa-solid fa-check-circle me-2 fs-5"></i> {{ session('success') }}
            </div>
            @endif
            @if (session('error'))
            <div class="alert alert-danger border-0 shadow-sm mb-4 d-flex align-items-center">
                <i class="fa-solid fa-circle-exclamation me-2 fs-5"></i> {{ session('error') }}
            </div>
            @endif

            <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
                <div class="card-header bg-white py-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-uppercase small text-muted">Detalles de la Cuota Origen</span>
                        <span class="badge rounded-pill bg-light text-dark border">Ref: Nº {{ $cuota->id }}</span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="text-muted small text-uppercase fw-bold d-block mb-1">Cliente</label>
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                    <i class="fa-solid fa-user-tie small"></i>
                                </div>
                                <span class="fw-bold text-dark fs-5">{{ $cuota->cliente->nombre }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small text-uppercase fw-bold d-block mb-1">Importe Total</label>
                            <span class="fs-4 fw-bold text-primary">
                                {{ number_format($cuota->importe, 2, ',', '.') }} {{ $cuota->cliente->moneda }}
                            </span>
                        </div>
                        <div class="col-12">
                            <label class="text-muted small text-uppercase fw-bold d-block mb-1">Concepto</label>
                            <p class="text-dark border-start border-3 ps-3 py-1 bg-light rounded-end">
                                {{ $cuota->concepto }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small text-uppercase fw-bold d-block mb-1">Fecha Emisión Cuota</label>
                            <span class="text-dark"><i class="fa-regular fa-calendar me-1 text-muted"></i> {{ $cuota->fecha_emision->format('d/m/Y') }}</span>
                        </div>
                    </div>

                    <hr class="my-4 opacity-50">

                    {{-- Lógica de Botones --}}
                    @php $factura = \App\Models\Factura::where('cuota_id', $cuota->id)->first(); @endphp

                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('cuotas.index') }}" class="btn btn-outline-dark px-4 rounded-pill">
                            <i class="fas fa-arrow-left me-1"></i> Volver al listado
                        </a>
                        @if(!$factura)
                        <form action="{{ route('facturas.generar', $cuota->id) }}" method="POST" class="w-100">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-lg shadow-sm px-4 rounded-pill">
                                <i class="fa-solid fa-wand-magic-sparkles me-2"></i>Generar Factura Legal
                            </button>
                        </form>
                        @else
                        <a href="{{ route('facturas.descargar', $factura->id) }}" class="btn btn-outline-dark px-4 rounded-pill">
                            <i class="fa-solid fa-file-pdf me-2 text-danger"></i>Descargar PDF
                        </a>

                        <form action="{{ route('facturas.enviar', $factura->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success px-4 rounded-pill shadow-sm">
                                <i class="fa-solid fa-paper-plane me-2"></i>Enviar al Cliente
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Columna Lateral: Estado del Proceso --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm bg-white" style="border-radius: 12px;">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-dark mb-4 small text-uppercase"><i class="fa-solid fa-timeline me-2 text-primary"></i>Estado del Proceso</h5>

                    <div class="position-relative ps-4 border-start ms-2">
                        {{-- Paso 1: Cuota --}}
                        <div class="mb-4">
                            <span class="position-absolute start-0 translate-middle-x bg-success rounded-circle" style="width: 12px; height: 12px; margin-top: 6px;"></span>
                            <h6 class="mb-1 fw-bold text-dark">Cuota Registrada</h6>
                            <p class="small text-muted mb-0">La transacción base está lista.</p>
                        </div>

                        {{-- Paso 2: Factura --}}
                        <div class="mb-4">
                            @if($factura)
                            <span class="position-absolute start-0 translate-middle-x bg-success rounded-circle" style="width: 12px; height: 12px; margin-top: 6px;"></span>
                            <h6 class="mb-1 fw-bold text-dark">Factura Generada</h6>
                            <p class="small text-muted mb-0">Documento <strong>{{ $factura->numero_factura }}</strong> creado.</p>
                            @else
                            <span class="position-absolute start-0 translate-middle-x bg-light border border-secondary rounded-circle" style="width: 12px; height: 12px; margin-top: 6px;"></span>
                            <h6 class="mb-1 fw-bold text-muted">Factura Pendiente</h6>
                            <p class="small text-muted mb-0">Esperando emisión del PDF legal.</p>
                            @endif
                        </div>

                        {{-- Paso 3: Envío --}}
                        <div class="mb-0">
                            <span class="position-absolute start-0 translate-middle-x bg-light border border-secondary rounded-circle" style="width: 12px; height: 12px; margin-top: 6px;"></span>
                            <h6 class="mb-1 fw-bold text-muted">Envío al Cliente</h6>
                            <p class="small text-muted mb-0">Notificación por email.</p>
                        </div>
                    </div>

                    @if($factura)
                    <div class="mt-4 p-3 bg-light rounded border border-dashed text-center">
                        <small class="d-block text-muted text-uppercase fw-bold mb-1" style="font-size: 0.65rem;">Sello Temporal</small>
                        <span class="text-dark small"><i class="fa-regular fa-clock me-1 text-primary"></i> {{ $factura->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <div class="mt-3 p-3 text-center">
                <p class="small text-muted">
                    <i class="fa-solid fa-shield-halved me-1"></i> Este proceso es irreversible tras la asignación de número correlativo.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection