@extends('layouts.app')

@section('titulo', 'Página de inicio')

@section('content')

<h2 class="mb-4">
    <i class="fas fa-home"></i>
    Panel de administración
</h2>

<div class="container">

    {{-- Mensaje de éxito --}}
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <div class="row mb-4">

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <i class="fas fa-users fa-2x text-primary me-3"></i>
                    <div>
                        <div class="text-muted">Clientes activos</div>
                        <h4 class="mb-0">{{ $clientesActivos }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <i class="fas fa-user-cog fa-2x text-success me-3"></i>
                    <div>
                        <div class="text-muted">Operarios</div>
                        <h4 class="mb-0">{{ $operariosActivos }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <i class="fas fa-tasks fa-2x text-warning me-3"></i>
                    <div>
                        <div class="text-muted">Tareas pendientes</div>
                        <h4 class="mb-0">{{ $tareasPendientes }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <i class="fas fa-file-invoice-dollar fa-2x text-danger me-3"></i>
                    <div>
                        <div class="text-muted">Cuotas pendientes</div>
                        <h4 class="mb-0">{{ $cuotasPendientes }}</h4>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <i class="fas fa-tasks"></i>
            Tareas pendientes para los próximos 5 días
        </div>

        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Operario</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tareasProximas as $tarea)
                    <tr>
                        <td>{{ $tarea->cliente->nombre }}</td>
                        <td>{{ $tarea->operario->name ?? 'Sin asignar' }}</td>
                        <td>
                            @if($tarea->completada)
                            <span class="badge bg-success">Completada</span>
                            @else
                            <span class="badge bg-warning text-dark">Pendiente</span>
                            @endif
                        </td>
                        <td>{{ $tarea->fecha_realizacion?->format('d/m/Y') }}</td>
                    </tr>

                    @empty

                    <tr>
                        <td colspan="4" class="text-center text-muted">
                            No hay tareas registradas
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>


    <div class="card shadow-sm">

        <div class="card-header d-flex justify-content-between align-items-center">
            <i class="fas fa-file-invoice-dollar"></i>
            Cuotas mensuales y excepcionales pendientes

            <span class="badge bg-danger">
                {{ number_format($importePendiente,2) }} €
            </span>
        </div>

        <ul class="list-group list-group-flush">

            @forelse($cuotas as $cuota)

            <li class="list-group-item d-flex justify-content-between">

                <span>{{ $cuota->cliente->nombre }}</span>

                <span class="badge bg-danger">
                    {{ number_format($cuota->importe,2) }} €
                </span>

            </li>

            @empty

            <li class="list-group-item text-center text-muted">
                No hay cuotas pendientes
            </li>

            @endforelse

        </ul>

    </div>
</div>
@endsection