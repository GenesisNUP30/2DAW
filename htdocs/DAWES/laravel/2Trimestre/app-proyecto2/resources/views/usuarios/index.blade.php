@extends('layouts.app')

@section('titulo', 'Gestor de tareas')

@section('content')

<div class="container-fluid mt-3">
    <div class="row">
        {{-- Navegación --}}
        <div class="col-md-2 bg-light" style="min-height: 90vh;">
            @include('layouts.nav')
        </div>

        <div class="col-md-10 p-4">


            <h1 class="mb-4">
                <i class="fas fa-clipboard-check"></i> Gestor de tareas
            </h1>

            {{-- Mensaje de éxito --}}
            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

            {{-- Tabla de usuarios --}}
            <div class="col-md-10">
                <h1>Lista de usuarios</h1>

                @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <a href="{{ route('usuarios.create') }}" class="btn btn-primary mb-3">Crear nuevo usuario</a>

                <div class="card">
                    <div class="card-body p-0">
                        <table class="table table-striped mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Correo</th>
                                    <th>Tipo</th>
                                    <th>Fecha de alta</th>
                                    <th class="text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($usuarios as $usu)
                                <tr>
                                    <td>{{ $usu->id }}</td>
                                    <td>{{ $usu->name }}</td>
                                    <td>{{ $usu->email }}</td>
                                    <td>{{ $usu->tipo }}</td>
                                    <td>{{ optional($usu->fecha_alta)->format('d/m/Y') }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('usuarios.edit', $usu) }}" class="btn btn-sm btn-warning">
                                            Editar
                                        </a>

                                        <a href="{{ route('usuarios.confirmDelete', $usu) }}"
                                            class="btn btn-sm btn-danger">
                                            Eliminar
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection