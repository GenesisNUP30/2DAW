<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('titulo', config('app.name', 'Gestor de tareas'))</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">


    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<style>
    .sidebar {
        min-height: calc(100vh - 60px);
        background-color: #f8f9fa;
        border-right: 1px solid #dee2e6;
        padding: 0;
    }

    .nav-link {
        color: #495057;
        padding: 0.8rem 1.2rem;
        border-radius: 0;
        margin: 0;
        border-bottom: 1px solid #eee;
        transition: background 0.2s;
    }

    .nav-link:hover {
        background-color: #e9ecef;
        color: #000;
    }

    .nav-link.active {
        background-color: #0d6efd !important;
        color: white !important;
    }

    .sidebar-heading {
        font-size: 0.75rem;
        text-transform: uppercase;
        font-weight: bold;
        color: #6c757d;
        padding: 1rem 1.2rem 0.5rem;
        background-color: #f1f1f1;
    }

    .btn-dark,
    .btn-secondary,
    .btn-primary,
    .btn-success {
        color: #fff !important;
    }

    .btn-dark i {
        color: #fff !important;
    }
</style>

<body>
    <div id="app">
        {{-- NAVBAR --}}
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm sticky-top">
            <div class="container-fluid px-4"> {{-- Usamos container-fluid para más ancho --}}
                <a class="navbar-brand fw-bold" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto align-items-center">
                        @auth
                        <li class="nav-item d-none d-md-flex align-items-center me-3">
                            <span class="text-muted small">
                                <strong>Tipo:</strong>
                                @if(Auth::user()->isAdmin())
                                <span class="text-primary"><i class="fas fa-user-shield"></i> Administrador</span>
                                @else
                                <span class="text-success"><i class="fas fa-hard-hat"></i> Operario</span>
                                @endif
                                <span class="mx-2">|</span>
                                <i class="far fa-clock me-1"></i>Última sesión: {{ Auth::user()->ultimaSesion() }}
                            </span>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-1"></i> {{ Auth::user()->name }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-end shadow-sm">
                                <a class="dropdown-item" href="{{ route('perfil') }}"><i class="fas fa-user-edit me-2"></i>Editar perfil</a>
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt me-2"></i>Salir</button>
                                </form>
                            </div>
                        </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>

        {{-- CUERPO DE LA APP --}}
        <div class="container-fluid p-0"> 
            <div class="row g-0"> 

                <aside class="col-md-3 col-lg-2 sidebar">
                    <nav class="nav flex-column">
                        @auth
                        {{-- Título dinámico según el rol --}}
                        <div class="sidebar-heading">
                            {{ Auth::user()->isAdmin() ? 'Navegación' : 'Mi Panel' }}
                        </div>
                        @if(Auth::user()->isAdmin())
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="fas fa-home me-2"></i>Inicio
                        </a>
                        <a class="nav-link {{ request()->routeIs('tareas.*') ? 'active' : '' }}" href="{{ route('tareas.index') }}">
                            <i class="fas fa-tasks me-2"></i>Tareas
                        </a>
                        <div class="sidebar-heading">Gestión</div>
                        <a class="nav-link {{ request()->routeIs('empleados.*') ? 'active' : '' }}" href="{{ route('empleados.index') }}">
                            <i class="fas fa-users me-2"></i>Empleados
                        </a>
                        <a class="nav-link {{ request()->routeIs('clientes.*') ? 'active' : '' }}" href="{{ route('clientes.index') }}">
                            <i class="fas fa-user-tie me-2"></i>Clientes
                        </a>
                        <a class="nav-link {{ request()->routeIs('cuotas.*') ? 'active' : '' }}" href="{{ route('cuotas.index') }}">
                            <i class="fas fa-file-invoice-dollar me-2"></i>Cuotas
                        </a>
                        @else
                        <a class="nav-link {{ request()->routeIs('tareas.*') ? 'active' : '' }}" href="{{ route('tareas.index') }}">
                            <i class="fas fa-tasks me-2"></i>Mis Tareas
                        </a>
                        @endif
                        @endauth
                    </nav>
                </aside>

                <main class="col-md-9 col-lg-10 p-4">
                    @yield('content')
                </main>
            </div>
        </div>
    </div>
</body>

</html>