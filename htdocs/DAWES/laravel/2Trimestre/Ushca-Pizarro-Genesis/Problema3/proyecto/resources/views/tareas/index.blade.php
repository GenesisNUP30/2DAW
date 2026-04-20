@extends('layouts.app')

@section('titulo', 'Listado de tareas')

@section('content')
<div class="container-fluid px-4 py-3">

    {{-- Cabecera --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold m-0">
            <i class="fas fa-tasks me-2"></i>Listado de tareas
        </h2>
        @if(auth()->user()->isAdmin())
        <a href="{{ route('tareas.create') }}" class="btn btn-dark btn-sm px-3">
            <i class="fas fa-plus me-1"></i> Nueva tarea
        </a>
        @endif
    </div>

    {{-- Mensaje de éxito --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Chips de filtro por estado --}}
    <div class="d-flex flex-wrap gap-2 mb-3 align-items-center">
        @php
            $estadoActual = request('estado', '');
            $chips = [
                '' =>  'Todas',
                'P' =>  'Pendientes',
                'B' =>  'En espera',
                'R' =>  'Realizadas',
                'C' =>  'Canceladas',
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
                <tr class="text-uppercase fw-bold text-muted" style="font-size: 0.75rem; letter-spacing: .05em;">
                    <th class="ps-4 py-3 border-0">Cliente</th>
                    <th class="py-3 border-0">Descripción</th>
                    <th class="py-3 border-0">Operario</th>
                    <th class="py-3 border-0">Fecha</th>
                    <th class="py-3 border-0">Estado</th>
                    <th class="py-3 pe-4 border-0 text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tareas as $tarea)
                <tr style="border-top: 1px solid #f3f4f6;">
                    <td class="ps-4">
                        <span class="fw-bold text-dark">{{ $tarea->cliente->nombre ?? '-' }}</span>
                    </td>
                    <td>
                        <span class="text-secondary d-inline-block" style="max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"
                              title="{{ $tarea->descripcion }}">
                            {{ $tarea->descripcion }}
                        </span>
                    </td>
                    <td>
                        @if($tarea->operario)
                        @php
                            $iniciales = collect(explode(' ', $tarea->operario->name))
                                ->take(2)->map(fn($p) => strtoupper($p[0]))->implode('');
                        @endphp
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center fw-semibold"
                                 style="width:32px;height:32px;background:#e0f2fe;color:#0369a1;font-size:12px;flex-shrink:0;">
                                {{ $iniciales }}
                            </div>
                            <span class="text-dark">{{ $tarea->operario->name }}</span>
                        </div>
                        @else
                        <span class="text-muted fst-italic">Sin asignar</span>
                        @endif
                    </td>
                    <td class="text-muted">
                        {{ $tarea->fecha_realizacion?->format('d/m/Y') ?? '-' }}
                    </td>
                    <td>
                        @php
                            $badges = [
                                'B' => ['bg-secondary bg-opacity-10 text-secondary', 'En espera'],
                                'P' => ['bg-warning bg-opacity-10 text-warning',    'Pendiente'],
                                'R' => ['bg-success bg-opacity-10 text-success',    'Realizada'],
                                'C' => ['bg-danger bg-opacity-10 text-danger',      'Cancelada'],
                            ];
                            [$clase, $texto] = $badges[$tarea->estado] ?? ['bg-light text-dark', $tarea->estado];
                        @endphp
                        <span class="badge rounded-pill border {{ $clase }} px-3 py-2" style="font-size:0.8rem;">
                            {{ $texto }}
                        </span>
                    </td>
                    <td class="pe-4 text-end">
                        <div class="d-flex gap-1 justify-content-end">

                            {{-- Ver --}}
                            <a href="{{ route('tareas.show', $tarea) }}"
                               class="btn btn-sm btn-light border"
                               style="width:34px;height:34px;display:flex;align-items:center;justify-content:center;"
                               title="Ver">
                                <i class="fas fa-eye text-info"></i>
                            </a>

                            @if(auth()->user()->isAdmin())
                            {{-- Editar --}}
                            <a href="{{ route('tareas.edit', $tarea) }}"
                               class="btn btn-sm btn-light border"
                               style="width:34px;height:34px;display:flex;align-items:center;justify-content:center;"
                               title="Editar">
                                <i class="fas fa-pen text-warning"></i>
                            </a>

                            {{-- Eliminar --}}
                            <a href="{{ route('tareas.confirmDelete', $tarea) }}"
                               class="btn btn-sm btn-light border"
                               style="width:34px;height:34px;display:flex;align-items:center;justify-content:center;"
                               title="Eliminar">
                                <i class="fas fa-trash text-danger"></i>
                            </a>
                            @endif

                            {{-- Completar (operario) --}}
                            @if(auth()->user()->isOperario() && $tarea->estado === 'P')
                            <a href="{{ route('tareas.completeForm', $tarea) }}"
                               class="btn btn-sm btn-light border"
                               style="width:34px;height:34px;display:flex;align-items:center;justify-content:center;"
                               title="Completar">
                                <i class="fas fa-check text-success"></i>
                            </a>
                            @endif

                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <i class="fas fa-clipboard-list fa-3x mb-2 d-block opacity-25"></i>
                        No hay tareas registradas.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    <div class="mt-3">
        {{ $tareas->onEachSide(1)->links() }}
    </div>

</div>
@endsection