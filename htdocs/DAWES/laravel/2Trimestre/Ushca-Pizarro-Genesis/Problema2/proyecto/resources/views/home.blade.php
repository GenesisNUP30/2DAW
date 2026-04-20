@extends('layouts.app')

@section('titulo', 'Panel de Control')

@section('content')
<div class="container-fluid px-4 py-3">

    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark m-0">
            <i class="fas fa-chart-line text-primary me-2"></i>Panel de Administración
        </h2>
    </div>

    {{-- 1. Fila de Indicadores (Counters) --}}
    <div class="row g-3 mb-4">
        @php
        $stats = [
        ['label' => 'Clientes', 'val' => $clientesActivos, 'icon' => 'users', 'color' => 'primary'],
        ['label' => 'Operarios', 'val' => $operariosActivos, 'icon' => 'user-cog', 'color' => 'success'],
        ['label' => 'Tareas Pendientes', 'val' => $tareasPendientes, 'icon' => 'tasks', 'color' => 'warning'],
        ['label' => 'Importe Total', 'val' => number_format($importePendiente, 0) . '€', 'icon' => 'wallet', 'color' => 'danger'],
        ];
        @endphp

        @foreach($stats as $stat)
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-{{ $stat['color'] }}">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-uppercase small fw-bold text-muted">{{ $stat['label'] }}</div>
                            <h3 class="mb-0 fw-bold">{{ $stat['val'] }}</h3>
                        </div>
                        <div class="bg-{{ $stat['color'] }} bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-{{ $stat['icon'] }} text-{{ $stat['color'] }} fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row g-4">
        {{-- COLUMNA IZQUIERDA (Principal) --}}
        <div class="col-lg-8">

            {{-- SECCIÓN: NUEVAS INCIDENCIAS (Prioridad Alta) --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                    <h5 class="mb-0 fw-bold text-info">
                        <i class="fas fa-exclamation-circle me-2"></i>Nuevas Incidencias
                    </h5>
                    <span class="badge bg-info bg-opacity-10 text-info px-3">{{ $incidenciasRecientes->count() }} esta semana</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 350px; overflow-y: auto;">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light sticky-top">
                                <tr class="small text-uppercase">
                                    <th class="ps-3">Cliente</th>
                                    <th>Fecha Registro</th>
                                    <th>Estado</th>
                                    <th class="text-center">Ver</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($incidenciasRecientes as $incidencia)
                                <tr>
                                    <td class="ps-3">
                                        <div class="fw-bold text-dark">{{ $incidencia->cliente->nombre }}</div>
                                        <div class="small text-muted text-truncate" style="max-width: 250px;">{{ $incidencia->descripcion }}</div>
                                    </td>
                                    <td class="small">{{ $incidencia->fecha_creacion?->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <span class="badge rounded-pill bg-{{ $incidencia->estado == 'B' ? 'secondary' : 'warning' }} text-{{ $incidencia->estado == 'B' ? 'white' : 'dark' }}">
                                            {{ $incidencia->estado == 'B' ? 'Nueva' : 'Pendiente' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('tareas.show', $incidencia) }}" class="btn btn-sm btn-light border">
                                            <i class="fas fa-chevron-right text-primary"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">No hay incidencias nuevas.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- SECCIÓN: PRÓXIMAS TAREAS --}}
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="mb-0 fw-bold text-success">
                        <i class="fas fa-calendar-check me-2"></i>Agenda Próximos 5 días
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr class="small text-uppercase">
                                    <th class="ps-3">Cliente / Operario</th>
                                    <th>Fecha de realización</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tareasProximas as $tarea)
                                <tr>
                                    <td class="ps-3">
                                        <div class="fw-bold text-dark">{{ $tarea->cliente->nombre }}</div>
                                        <div class="small text-muted"><i class="fas fa-user-worker me-1 small"></i>{{ $tarea->operario->name ?? 'Sin asignar' }}</div>
                                    </td>
                                    <td>{{ $tarea->fecha_realizacion?->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $tarea->completada ? 'success' : 'warning' }}-subtle text-{{ $tarea->completada ? 'success' : 'warning' }} border">
                                            {{ $tarea->completada ? 'Hecha' : 'Pendiente' }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4">Sin tareas programadas.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- COLUMNA DERECHA (Secundaria) --}}
        <div class="col-lg-4">

            {{-- CARD: CUOTAS PENDIENTES --}}
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-danger bg-opacity-10 text-danger">
                    <h5 class="mb-0 fw-bold d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-file-invoice-dollar me-2"></i>Cuotas</span>
                        <span class="badge bg-white text-danger">{{ number_format($importePendiente, 2) }} €</span>
                    </h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush" style="max-height: 600px; overflow-y: auto;">
                        @forelse($cuotas as $cuota)
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <div>
                                <div class="fw-bold text-dark small">{{ $cuota->cliente->nombre }}</div>
                                <div class="text-muted x-small">Pendiente de cobro</div>
                            </div>
                            <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill">
                                {{ number_format($cuota->importe, 2) }} €
                            </span>
                        </li>
                        @empty
                        <li class="list-group-item text-center py-4 text-muted">Todo al día.</li>
                        @endforelse
                    </ul>

                    @if($cuotasPendientes > 3)
                    <div class="px-3 py-2 bg-danger bg-opacity-10 border-top border-danger border-opacity-25">
                        <small class="text-danger">
                            <i class="fas fa-info-circle me-1"></i>
                            Hay {{ $cuotasPendientes - 3 }} cuota{{ $cuotasPendientes - 3 > 1 ? 's' : '' }} más sin mostrar
                        </small>
                    </div>
                    @endif

                    {{-- footer --}}
                    <div class="card-footer bg-white d-flex justify-content-between align-items-center py-2">
                        <small class="text-muted">{{ $cuotasPendientes }} cuota{{ $cuotasPendientes != 1 ? 's' : '' }} pendientes</small>
                        <a href="{{ route('cuotas.index') }}" class="btn btn-sm btn-outline-danger">
                            Ver todas <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
    .x-small {
        font-size: 0.75rem;
    }

    .sticky-top {
        top: -1px;
        z-index: 10;
    }

    .list-group-item:hover {
        background-color: #f8f9fa;
    }

    /* Estilo para el scrollbar de las tablas */
    ::-webkit-scrollbar {
        width: 6px;
    }

    ::-webkit-scrollbar-thumb {
        background: #e0e0e0;
        border-radius: 10px;
    }
</style>
@endsection