@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Listado de Artículos</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Botón para crear -->
    <a href="{{ route('articulos.create') }}" class="btn btn-primary mb-3">Crear nuevo artículo</a>

    <!-- Formulario de filtrado y ordenación -->
    <form method="GET" class="mb-4">
        <div class="row g-2">
            <!-- Búsqueda por título -->
            <div class="col-md-4">
                <input type="text" name="busqueda" class="form-control" 
                       placeholder="Buscar por título..." 
                       value="{{ request('busqueda') }}">
            </div>

            <!-- Filtro por categoría (opcional) -->
            {{-- 
            <div class="col-md-3">
                <select name="categoria_id" class="form-select">
                    <option value="">Todas las categorías</option>
                    @foreach($categorias as $id => $nombre)
                        <option value="{{ $id }}" {{ request('categoria_id') == $id ? 'selected' : '' }}>
                            {{ $nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            --}}

            <!-- Ordenación -->
            <div class="col-md-3">
                <select name="orden" class="form-select">
                    <option value="created_at|desc" {{ request('orden') == 'created_at|desc' ? 'selected' : '' }}>
                        Más recientes
                    </option>
                    <option value="created_at|asc" {{ request('orden') == 'created_at|asc' ? 'selected' : '' }}>
                        Más antiguos
                    </option>
                    <option value="titulo|asc" {{ request('orden') == 'titulo|asc' ? 'selected' : '' }}>
                        Título A-Z
                    </option>
                    <option value="titulo|desc" {{ request('orden') == 'titulo|desc' ? 'selected' : '' }}>
                        Título Z-A
                    </option>
                </select>
            </div>

            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-secondary w-100">Filtrar</button>
            </div>
        </div>
    </form>

    <!-- Tabla de resultados -->
    @if($articulos->count())
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Resumen</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($articulos as $art)
                        <tr>
                            <td>{{ $art->titulo }}</td>
                            <td>{{ Str::limit($art->contenido, 50) }}</td>
                            <td>{{ $art->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('articulos.show', $art) }}" class="btn btn-sm btn-info">Ver</a>
                                <a href="{{ route('articulos.edit', $art) }}" class="btn btn-sm btn-warning">Editar</a>
                                <form action="{{ route('articulos.destroy', $art) }}" method="POST" style="display:inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('¿Eliminar?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        {{ $articulos->appends(request()->query())->links() }}

    @else
        <div class="alert alert-warning">No hay artículos que coincidan con los filtros.</div>
    @endif
</div>
@endsection