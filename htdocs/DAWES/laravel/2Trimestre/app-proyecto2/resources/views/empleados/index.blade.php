@extends('layouts.app')

@section('titulo', 'Lista de empleados')

@section('content')
<div class="container py-3">

    {{-- Cabecera --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold m-0">
            <i class="fas fa-users text-primary me-2"></i>Lista de empleados
        </h2>
        @if(auth()->user()->isAdmin())
        <a href="{{ route('empleados.create') }}" class="btn btn-dark btn-sm px-3">
            <i class="fas fa-plus me-1"></i> Añadir empleado
        </a>
        @endif
    </div>

    {{-- Mensajes --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Chips de filtro --}}
    <div class="d-flex flex-wrap gap-2 mb-3">
        @php
            $estadoActual = request('estado', '');
            $chips = [
                ''      => 'Todos',
                'activo' => 'Activos',
                'baja'   => 'De baja',
            ];
        @endphp
        @foreach($chips as $valor => $etiqueta)
        <a href="{{ request()->fullUrlWithQuery(['estado' => $valor]) }}"
           class="badge rounded-pill text-decoration-none border
                  {{ $estadoActual === $valor ? 'bg-dark text-white border-dark' : 'bg-white text-muted border-secondary' }}"
           style="font-size: 0.78rem; padding: 6px 14px;">
            {{ $etiqueta }}
        </a>
        @endforeach
    </div>

    {{-- Tabla --}}
    <div class="card border shadow-sm" style="border-color: #e5e7eb !important; border-radius: 12px; overflow: hidden;">
        <table class="table table-hover align-middle mb-0">
            <thead style="background: #f9fafb;">
                <tr class="text-uppercase text-muted fw-bold"
                    style="font-size: 0.7rem; letter-spacing: .05em;">
                    <th class="ps-4 py-3 border-0">Empleado</th>
                    <th class="py-3 border-0">Contacto</th>
                    <th class="py-3 border-0">Tipo</th>
                    <th class="py-3 border-0">Estado</th>
                    <th class="py-3 pe-4 border-0 text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($empleados as $empleado)
                <tr style="border-top: 1px solid #f3f4f6;">

                    {{-- Empleado: avatar + nombre --}}
                    <td class="ps-4">
                        @php
                            $iniciales = collect(explode(' ', $empleado->name))
                                ->take(2)->map(fn($p) => strtoupper($p[0]))->implode('');
                            $bgColor = $empleado->isBaja() ? '#f3f4f6' : '#e0f2fe';
                            $textColor = $empleado->isBaja() ? '#9ca3af' : '#0369a1';
                        @endphp
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center fw-semibold"
                                 style="width:34px;height:34px;background:{{ $bgColor }};color:{{ $textColor }};font-size:12px;flex-shrink:0;">
                                {{ $iniciales }}
                            </div>
                            <div>
                                <div class="fw-semibold {{ $empleado->isBaja() ? 'text-muted' : 'text-dark' }}">
                                    {{ $empleado->name }}
                                </div>
                                <div style="font-size:11px;color:#9ca3af;">
                                    Última sesión: {{ $empleado->ultimaSesion() }}
                                </div>
                            </div>
                        </div>
                    </td>

                    {{-- Contacto --}}
                    <td>
                        <div>{{ $empleado->email }}</div>
                        <div style="font-size:12px;color:#9ca3af;">
                            {{ $empleado->telefono ?? 'Sin teléfono' }}
                        </div>
                    </td>

                    {{-- Tipo --}}
                    <td>
                        @if($empleado->tipo === 'administrador')
                        <span class="badge rounded-pill border bg-purple bg-opacity-10 border-purple"
                              style="font-size:.75rem;padding:4px 10px;background:#f3e8ff;color:#7e22ce;border-color:#d8b4fe !important;">
                            Administrador
                        </span>
                        @else
                        <span class="badge rounded-pill border bg-info bg-opacity-10 text-info border-info"
                              style="font-size:.75rem;padding:4px 10px;">
                            Operario
                        </span>
                        @endif
                    </td>

                    {{-- Estado --}}
                    <td>
                        @if($empleado->isBaja())
                        <span class="badge rounded-pill border bg-secondary bg-opacity-10 text-secondary border-secondary"
                              style="font-size:.75rem;padding:4px 10px;">
                            De baja
                        </span>
                        @else
                        <span class="badge rounded-pill border bg-success bg-opacity-10 text-success border-success"
                              style="font-size:.75rem;padding:4px 10px;">
                            Activo
                        </span>
                        @endif
                    </td>

                    {{-- Acciones --}}
                    <td class="pe-4">
                        <div class="d-flex gap-1 justify-content-end">
                            @if(!$empleado->isBaja())
                            <a href="{{ route('empleados.edit', $empleado) }}"
                               class="btn btn-sm btn-light border"
                               style="width:30px;height:30px;padding:0;display:flex;align-items:center;justify-content:center;"
                               title="Editar">
                                <i class="fas fa-pen text-warning" style="font-size:12px;"></i>
                            </a>
                            <a href="{{ route('empleados.confirmBaja', $empleado) }}"
                               class="btn btn-sm btn-light border"
                               style="width:30px;height:30px;padding:0;display:flex;align-items:center;justify-content:center;"
                               title="Dar de baja">
                                <i class="fas fa-user-minus text-info" style="font-size:12px;"></i>
                            </a>
                            <a href="{{ route('empleados.confirmDelete', $empleado) }}"
                               class="btn btn-sm btn-light border"
                               style="width:30px;height:30px;padding:0;display:flex;align-items:center;justify-content:center;"
                               title="Eliminar">
                                <i class="fas fa-trash text-danger" style="font-size:12px;"></i>
                            </a>
                            @else
                            <a href="{{ route('empleados.confirmAlta', $empleado) }}"
                               class="btn btn-sm btn-light border"
                               style="width:30px;height:30px;padding:0;display:flex;align-items:center;justify-content:center;"
                               title="Reactivar">
                                <i class="fas fa-user-check text-success" style="font-size:12px;"></i>
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">
                        <i class="fas fa-users fa-2x mb-2 d-block opacity-25"></i>
                        No hay empleados registrados.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    <div class="mt-3">
        {{ $empleados->links() }}
    </div>

</div>
@endsection