@extends('layouts.app')

@section('content')
<div class="container">
    <h1><i class="fa-solid fa-file-invoice"></i> Gestión de Factura</h1>
    
    <div class="card mt-4">
        <div class="card-header bg-dark text-white">
            Datos de la Cuota #{{ $cuota->id }}
        </div>
        <div class="card-body">
            <p><strong>Cliente:</strong> {{ $cuota->cliente->nombre }}</p>
            <p><strong>Concepto:</strong> {{ $cuota->concepto }}</p>
            <p><strong>Importe:</strong> {{ $cuota->importe }} {{ $cuota->cliente->moneda }}</p>

            <hr>

            @php $factura = \App\Models\Factura::where('cuota_id', $cuota->id)->first(); @endphp

            @if(!$factura)
                {{-- Si no existe factura, botón para crearla --}}
                <form action="{{ route('facturas.generar', $cuota->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-gear"></i> Generar Registro y PDF
                    </button>
                </form>
            @else
                {{-- Si ya existe, botones de descargar y enviar --}}
                <div class="alert alert-info">Esta cuota ya tiene una factura generada ({{ $factura->numero_factura }})</div>
                
                <a href="{{ route('facturas.descargar', $factura->id) }}" class="btn btn-secondary">
                    <i class="fa-solid fa-download"></i> Descargar PDF
                </a>

                <form action="{{ route('facturas.enviar', $factura->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="fa-solid fa-paper-plane"></i> Enviar por Email
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection