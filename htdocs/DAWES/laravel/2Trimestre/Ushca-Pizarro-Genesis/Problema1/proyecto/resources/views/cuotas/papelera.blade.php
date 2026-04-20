@extends('layouts.app')

@section('titulo', 'Papelera de Cuotas')

@section('content')
<div class="container-fluid px-4 py-3">

    {{-- Cabecera --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold m-0 text-dark">
                <i class="fas fa-trash-restore me-2 text-danger"></i>Papelera de Cuotas
            </h2>
            <p class="text-muted small mb-0">Recupera cuotas eliminadas accidentalmente.</p>
        </div>
        <a href="{{ route('cuotas.index') }}" class="btn btn-sm btn-light border shadow-sm px-3">
            <i class="fas fa-arrow-left me-1"></i> Volver al listado
        </a>
    </div>

    {{-- Mensajes de Feedback --}}
    @if (session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Cuerpo de la Papelera --}}
    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 text-muted small fw-bold text-uppercase">Cliente</th>
                        <th class="text-muted small fw-bold text-uppercase">Concepto</th>
                        <th class="text-muted small fw-bold text-uppercase">Importe</th>
                        <th class="text-muted small fw-bold text-uppercase">Eliminado el</th>
                        <th class="text-end pe-4 text-muted small fw-bold text-uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($cuotasEliminadas as $cuota)
                    <tr class="bg-hover-light">
                        <td class="ps-4">
                            <div class="fw-bold text-dark">{{ $cuota->cliente->nombre ?? 'Cliente no disponible' }}</div>
                            <div class="text-muted small">ID Cuota: #{{ $cuota->id }}</div>
                        </td>
                        <td>
                            <span class="text-muted">{{ Str::limit($cuota->concepto, 40) }}</span>
                        </td>
                        <td>
                            <span class="fw-bold text-dark">{{ number_format($cuota->importe, 2, ',', '.') }} €</span>
                        </td>
                        <td>
                            <div class="text-dark small">{{ $cuota->deleted_at->format('d/m/Y') }}</div>
                            <div class="text-muted" style="font-size: 0.75rem;">{{ $cuota->deleted_at->format('H:i') }} hs</div>
                        </td>
                        <td class="text-end pe-4">
                            <form action="{{ route('cuotas.restore', $cuota->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-success rounded-pill px-3 shadow-sm">
                                    <i class="fas fa-undo me-1"></i> Restaurar
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <div class="mb-3">
                                <i class="fas fa-trash fa-3x opacity-10"></i>
                            </div>
                            <h5 class="fw-light">La papelera está vacía</h5>
                            <p class="small">No hay elementos eliminados recientemente.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Paginación --}}
    <div class="mt-4 d-flex justify-content-center">
        {{ $cuotasEliminadas->links() }}
    </div>
</div>

<style>
    .bg-hover-light:hover {
        background-color: #fcfcfc;
        transition: background-color 0.2s ease;
    }
</style>
@endsection