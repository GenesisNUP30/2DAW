@extends('layouts.app')

@section('content')

<h1 class="mb-4">
    <i class="fas fa-clipboard-check"></i> Gestor de tareas
</h1>

{{-- Mensaje de éxito --}}
@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="container">
    <!-- Resumen estadístico -->
    <div class="row mb-4">
        <!-- Tarjetas según rol -->
    </div>

    <div class="row">
        <!-- Columna principal: Tareas recientes + Vencimientos -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-tasks me-2"></i> Mis tareas recientes</h5>
                </div>
                <div class="card-body">
                    <!-- Tabla de tareas -->
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i> Próximos vencimientos</h5>
                </div>
                <div class="card-body">
                    <!-- Lista de vencimientos -->
                </div>
            </div>
        </div>

        <!-- Barra lateral: Acciones rápidas + Alertas -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-bolt me-2"></i> Acciones rápidas</h5>
                </div>
                <div class="card-body">
                    <!-- Botones de acción -->
                </div>
            </div>


        </div>
    </div>
</div>
@endsection
