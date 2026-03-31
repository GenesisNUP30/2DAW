@extends('layouts.app')

@section('titulo', 'Confirmar eliminación de cuota')

@section('content')
<div class="container py-4">

    {{-- Cabecera --}}
    <div class="mb-4">
        <h2 class="fw-bold m-0 text-dark">
            <i class="fas fa-trash-alt me-2 text-danger"></i>Eliminar cuota
        </h2>
    </div>

    {{-- Bloque de Advertencia --}}
    <div class="card border-0 mb-4" style="border-radius: 12px; background-color: #fef2f2; border: 1px solid #fee2e2 !important;">
        <div class="card-body d-flex align-items-start gap-3 py-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                style="width:45px; height:45px; background-color: #fee2e2;">
                <i class="fas fa-exclamation-triangle text-danger"></i>
            </div>
            <div>
                <h5 class="fw-bold text-danger mb-1">¿Estás seguro de que deseas eliminar esta cuota?</h5>
                <p class="text-muted mb-0 small">
                    La cuota <strong>nº {{ $cuota->id }}</strong> será enviada a la papelera.
                    Podrás restaurarla más tarde si lo necesitas, y la <strong>factura asociada no se borrará</strong> del sistema.
                </p>
            </div>
        </div>
    </div>

    {{-- Resumen de la Cuota a eliminar --}}
    <div class="card border shadow-sm mb-4" style="border-radius: 12px; border-color: #e5e7eb !important;">
        <div class="card-header bg-light border-bottom py-2 px-3"
            style="font-size:.7rem; font-weight:600; text-transform:uppercase; letter-spacing:.06em; color:#6b7280;">
            Detalles del registro
        </div>
        <div class="card-body px-3 py-1">

            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted small">Cliente</span>
                <span class="fw-bold text-dark">{{ $cuota->cliente->nombre ?? '—' }}</span>
            </div>

            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted small">Concepto</span>
                <span class="text-dark">{{ $cuota->concepto }}</span>
            </div>

            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted small">Importe</span>
                <span class="fw-bold text-danger">{{ number_format($cuota->importe, 2, ',', '.') }} €</span>
            </div>

            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted small">Fecha de emisión</span>
                <span class="text-dark">{{ optional($cuota->fecha_emision)->format('d/m/Y') ?? '—' }}</span>
            </div>

            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted small">Estado de pago</span>
                <span>
                    @if($cuota->isPagada())
                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">
                        Pagada el {{ $cuota->fecha_pago->format('d/m/Y') }}
                    </span>
                    @else
                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25">
                        Pendiente
                    </span>
                    @endif
                </span>
            </div>

            @if($cuota->notas)
            <div class="py-2">
                <span class="text-muted small d-block mb-1">Notas:</span>
                <div class="p-2 bg-light rounded small text-muted border">
                    {{ $cuota->notas }}
                </div>
            </div>
            @endif

        </div>
    </div>

    {{-- Formulario de Acción --}}
    <form action="{{ route('cuotas.destroy', $cuota) }}" method="POST">
        @csrf
        @method('DELETE')
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-danger px-4 fw-bold shadow-sm">
                <i class="fas fa-trash-alt me-2"></i>Sí, eliminar cuota
            </button>
            <a href="{{ route('cuotas.index') }}" class="btn btn-light border px-4 text-muted">
                <i class="fas fa-times me-1"></i> Cancelar
            </a>
        </div>
    </form>

</div>
@endsection