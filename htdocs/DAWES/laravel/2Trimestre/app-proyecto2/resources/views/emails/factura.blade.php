@extends('layouts.app') 

@section('content')
<div class="container">
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h2 class="text-primary"><i class="fas fa-file-invoice me-2"></i> Nueva Factura Disponible</h2>
            <hr>
            <p>Estimado/a <strong>{{ $factura->cliente_nombre }}</strong>,</p>
            <p>Le informamos que se ha generado la factura <strong>{{ $factura->numero_factura }}</strong> correspondiente a su cuota de servicio.</p>
            
            <div class="bg-light p-3 rounded mb-3">
                <p class="mb-1"><strong>Concepto:</strong> {{ $factura->concepto }}</p>
                <p class="mb-0"><strong>Importe total:</strong> {{ number_format($factura->importe, 2, ',', '.') }} {{ $factura->moneda }}</p>
            </div>

            <p>Adjunto a este correo encontrará el documento original en formato PDF.</p>
            
            <p class="text-muted mt-4">Gracias por confiar en <strong>SiempreColgando</strong>.</p>
        </div>
    </div>
</div>
@endsection