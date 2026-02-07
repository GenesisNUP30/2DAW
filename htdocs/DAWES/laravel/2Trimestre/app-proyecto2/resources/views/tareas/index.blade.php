@extends('layouts.app')

@section('titulo', 'Gestor de tareas')

@section('content')

<div class="container">

    <h1 class="mb-4">
        <i class="fas fa-clipboard-check"></i> Gestor de tareas
    </h1>

    {{-- Mensaje de éxito --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tabla de tareas --}}
    <div class="card">
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Descripción</th>
                        <th>Operario</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tareas as $tarea)
                        <tr>
                            <td>{{ $tarea->id }}</td>
                            <td>{{ $tarea->cliente->nombre ?? '-' }}</td>
                            <td>{{ $tarea->descripcion }}</td>
                            <td>{{ $tarea->operario->name ?? '-' }}</td>
                            <td>{{ optional($tarea->fecha_realizacion)->format('d/m/Y') }}</td>
                            <td>
                                @if ($tarea->estado === 'B')
                                    <span class="badge bg-secondary">Esperando aprobación</span>
                                @elseif ($tarea->estado === 'P')
                                    <span class="badge bg-warning text-dark">Pendiente</span>
                                @elseif ($tarea->estado === 'R')
                                    <span class="badge bg-success">Realizada</span>
                                @elseif ($tarea->estado === 'C')
                                    <span class="badge bg-danger">Cancelada</span>
                                @endif
                            </td>
                            <td class="text-end">

                                {{-- Ver --}}
                                <a href="{{ route('tareas.show', $tarea) }}" class="btn btn-sm btn-info">
                                    <i class="far fa-eye"></i>
                                    Ver
                                </a>

                                {{-- Admin --}}
                                @if (auth()->user()->isAdmin())
                                    <a href="{{ route('tareas.edit', $tarea) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                        Editar
                                    </a>

                                    <a href="{{ route('tareas.confirmDelete', $tarea) }}"
                                       class="btn btn-sm btn-danger">
                                       <i class="fas fa-trash-alt"></i>
                                        Eliminar
                                    </a>
                                @endif

                                {{-- Operario --}}
                                @if (auth()->user()->isOperario() && $tarea->estado === 'P')
                                    <a href="{{ route('tareas.completeForm', $tarea) }}"
                                       class="btn btn-sm btn-success">
                                        Completar
                                    </a>
                                @endif

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-3">
                                No hay tareas registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Paginación --}}
    <div class="mt-3">
        {{ $tareas->links() }}
    </div>

</div>
@endsection
