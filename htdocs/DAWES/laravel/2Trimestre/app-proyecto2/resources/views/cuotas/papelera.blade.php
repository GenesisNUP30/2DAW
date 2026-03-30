@extends('layouts.app')

@section('titulo', 'Papelera de Cuotas')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-trash-restore me-2 text-danger"></i> Cuotas Eliminadas</h1>
        <a href="{{ route('cuotas.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver al listado
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Cliente</th>
                        <th>Concepto</th>
                        <th>Importe</th>
                        <th>Fecha Eliminación</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($cuotasEliminadas as $cuota)
                    <tr>
                        <td>{{ $cuota->cliente->nombre ?? 'Cliente eliminado' }}</td>
                        <td>{{ $cuota->concepto }}</td>
                        <td>{{ number_format($cuota->importe, 2) }} €</td>
                        <td>{{ $cuota->deleted_at->format('d/m/Y H:i') }}</td>
                        <td class="text-center">
                            <form action="{{ route('cuotas.restore', $cuota->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">
                                    <i class="fas fa-undo"></i> Restaurar
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            La papelera está vacía.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $cuotasEliminadas->links() }}
    </div>
</div>
@endsection