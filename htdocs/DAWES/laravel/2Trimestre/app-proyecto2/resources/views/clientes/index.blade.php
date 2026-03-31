@extends('layouts.app')

@section('titulo', 'Listado de clientes')

@section('content')
<div class="container-fluid px-4 py-3">

    {{-- Cabecera --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold m-0 text-dark">
            <i class="fas fa-user-tie me-2"></i>Listado de clientes
        </h2>
        @if(auth()->user()->isAdmin())
        <a href="{{ route('clientes.create') }}" class="btn btn-dark btn-sm px-3 shadow-sm">
            <i class="fas fa-plus me-1"></i> Añadir cliente
        </a>
        @endif
    </div>

    {{-- Alertas de sesión --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Chips de filtro --}}
    <div class="d-flex flex-wrap gap-3 mb-4 align-items-center">
        {{-- Grupo de Estado --}}
        <div class="d-flex gap-2 align-items-center">
            <span class="text-muted fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.05em;">Estado:</span>
            @php $est = request('estado'); @endphp
            <a href="{{ request()->fullUrlWithQuery(['estado' => '']) }}"
                class="badge rounded-pill text-decoration-none border {{ $est == '' ? 'bg-dark text-white border-dark' : 'bg-white text-muted border-secondary' }}"
                style="padding: 6px 14px;">Todos</a>

            <a href="{{ request()->fullUrlWithQuery(['estado' => 'activos']) }}"
                class="badge rounded-pill text-decoration-none border {{ $est == 'activos' ? 'bg-success text-white border-success' : 'bg-white text-muted border-secondary' }}"
                style="padding: 6px 14px;">Activos</a>

            <a href="{{ request()->fullUrlWithQuery(['estado' => 'baja']) }}"
                class="badge rounded-pill text-decoration-none border {{ $est == 'baja' ? 'bg-secondary text-white border-secondary' : 'bg-white text-muted border-secondary' }}"
                style="padding: 6px 14px;">En Baja</a>
        </div>

        <div class="vr text-muted opacity-25 d-none d-md-block" style="height: 20px;"></div>

        {{-- Grupo de Pagos --}}
        <div class="d-flex gap-2 align-items-center">
            <span class="text-muted fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.05em;">Pagos:</span>
            @php $pag = request('pago'); @endphp
            <a href="{{ request()->fullUrlWithQuery(['pago' => 'pendiente']) }}"
                class="badge rounded-pill text-decoration-none border {{ $pag == 'pendiente' ? 'bg-danger text-white border-danger shadow-sm' : 'bg-white text-muted border-secondary' }}"
                style="padding: 6px 14px;">
                <i class="fas fa-exclamation-circle me-1"></i> Con deuda
            </a>

            @if($pag)
            <a href="{{ route('clientes.index') }}" class="text-muted small text-decoration-none ms-2">
                <i class="fas fa-times-circle"></i> Limpiar
            </a>
            @endif
        </div>
    </div>

    {{-- Tabla de Clientes --}}
    <div class="card border shadow-sm" style="border-color: #e5e7eb !important; border-radius: 12px; overflow: hidden;">
        <table class="table table-hover align-middle mb-0">
            <thead style="background: #f9fafb;">
                <tr class="text-uppercase fw-bold text-muted" style="font-size: 0.75rem; letter-spacing: .05em;">
                    <th class="ps-4 py-3 border-0">Cliente / CIF</th>
                    <th class="py-3 border-0">Contacto</th>
                    <th class="py-3 border-0">País</th>
                    <th class="py-3 border-0 text-center">Cuotas</th>
                    <th class="py-3 border-0">Estado</th>
                    <th class="py-3 pe-4 border-0 text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($clientes as $cliente)
                <tr style="border-top: 1px solid #f3f4f6;">
                    {{-- Nombre y CIF --}}
                    <td class="ps-4">
                        <div class="fw-bold text-dark">{{ $cliente->nombre }}</div>
                        <div class="text-muted">{{ $cliente->cif }}</div>
                    </td>

                    {{-- Contacto --}}
                    <td>
                        <div class="text-dark"><i class="fas fa-phone-alt me-1 opacity-50"></i> {{ $cliente->telefono }}</div>
                        <div class="text-muted">{{ $cliente->correo }}</div>
                    </td>

                    {{-- País --}}
                    <td>
                        <span class="text-secondary">
                            {{ $cliente->paisRelacion ? $cliente->paisRelacion->nombre : '—' }}
                        </span>
                    </td>

                    {{-- Indicador de Cuotas --}}
                    <td class="text-center">
                        @if($cliente->tieneCuotasPendientes())
                        <span class="badge rounded-pill border bg-danger bg-opacity-10 text-danger px-3 py-2" title="Tiene cobros pendientes">
                            <i class="fas fa-file-invoice-dollar me-1"></i> Pendiente
                        </span>
                        @else
                        <span class="badge rounded-pill bg-success bg-opacity-10 text-success px-3 py-2">
                            <i class="fas fa-check me-1"></i> Al día
                        </span>
                        @endif
                    </td>

                    {{-- Estado (Activo/Baja) --}}
                    <td>
                        @if($cliente->isActivo())
                        <span class="badge rounded-pill border bg-success bg-opacity-10 text-success px-3 py-2">
                            Activo
                        </span>
                        @else
                        <span class="badge rounded-pill border bg-secondary bg-opacity-10 text-secondary px-3 py-2">
                            En baja
                        </span>
                        @endif
                    </td>

                    {{-- Acciones --}}
                    <td class="pe-4">
                        <div class="d-flex gap-1 justify-content-end">
                            {{-- Ver --}}
                            <a href="{{ route('clientes.show', $cliente) }}"
                                class="btn btn-sm btn-light border"
                                style="width:34px; height:34px; display:flex; align-items:center; justify-content:center;"
                                title="Ver ficha">
                                <i class="fas fa-eye text-info"></i>
                            </a>

                            @if($cliente->isActivo())
                            {{-- Botón Baja --}}
                            <a href="{{ route('clientes.confirmBaja', $cliente) }}"
                                class="btn btn-sm btn-light border"
                                style="width:34px; height:34px; display:flex; align-items:center; justify-content:center;"
                                title="Dar de baja">
                                <i class="fas fa-user-minus text-warning"></i>
                            </a>
                            @else
                            {{-- Botón Alta/Reactivar --}}
                            <a href="{{ route('clientes.confirmAlta', $cliente) }}"
                                class="btn btn-sm btn-light border"
                                style="width:34px; height:34px; display:flex; align-items:center; justify-content:center;"
                                title="Reactivar">
                                <i class="fas fa-user-check text-success"></i>
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <i class="fas fa-users fa-3x mb-2 d-block opacity-25"></i>
                        No hay clientes registrados en la base de datos.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    <div class="mt-3">
        {{ $clientes->links() }}
    </div>
</div>
@endsection