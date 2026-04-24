<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('titulo', config('app.name', 'Gestor de tareas'))</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        /* Estilos del Sidebar */
        .sidebar {
            min-height: calc(100vh - 60px);
            background-color: #f8f9fa;
            border-right: 1px solid #dee2e6;
            padding: 0;
        }

        .nav-link {
            color: #495057;
            padding: 0.8rem 1.2rem;
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

        /* Botones */
        .btn-dark,
        .btn-secondary,
        .btn-primary,
        .btn-success {
            color: #fff !important;
        }

        .btn-dark i {
            color: #fff !important;
        }

        /* Estilo para los submenús */
        .collapse .nav-link {
            padding-left: 2.5rem;
            /* Más sangría para los hijos */
            font-size: 0.85rem;
            background-color: #fcfcfc;
        }

        /* Rotación del icono cuando se abre */
        [aria-expanded="true"] .fa-chevron-down {
            transform: rotate(180deg);
            transition: transform 0.2s;
        }

        [aria-expanded="false"] .fa-chevron-down {
            transition: transform 0.2s;
        }

        /* Color suave para los ítems deshabilitados */
        .text-muted {
            opacity: 0.6;
            cursor: not-allowed;
        }
    </style>
</head>

<body>
    <div id="app">
        {{-- NAVBAR --}}
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm sticky-top">
            <div class="container-fluid px-4">
                <a class="navbar-brand fw-bold" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto align-items-center">
                        @guest
                        @if (Route::has('login'))
                        <li class="nav-item mt-1">
                            <a class="nav-link border-0" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @endif
                        <!-- @if (Route::has('register'))
                                <li class="nav-item mt-1">
                                    <a class="nav-link border-0" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif -->
                        @else
                        {{-- Info Usuario Logueado --}}
                        <li class="nav-item d-none d-md-flex align-items-center me-3 small text-muted">
                            <strong>Tipo:</strong>
                            @if(Auth::user()->isAdmin())
                            <span class="text-primary ms-1"><i class="fas fa-user-shield"></i> Admin</span>
                            @else
                            <span class="text-success ms-1"><i class="fas fa-hard-hat"></i> Operario</span>
                            @endif
                            <span class="mx-2">|</span>
                            <i class="far fa-clock me-1"></i>{{ Auth::user()->ultimaSesion() }}
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle border-0" href="#" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-1"></i> {{ Auth::user()->name }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-end shadow-sm">
                                <a class="dropdown-item" href="{{ route('perfil') }}"><i class="fas fa-user-edit me-2"></i>Editar Perfil</a>
                                <hr class="dropdown-divider">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt me-2"></i>Salir</button>
                                </form>
                            </div>
                        </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        {{-- CUERPO DE LA APP --}}
        <div class="container-fluid p-0">
            <div class="row g-0">

                @auth
                {{-- SI ESTÁ LOGUEADO: Sidebar + Main 10 columnas --}}
                <aside class="col-md-3 col-lg-2 sidebar d-none d-md-block">
                    <nav class="nav flex-column">
                        <div class="sidebar-heading">Navegación</div>
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="fas fa-home me-2"></i>Inicio
                        </a>
                        <a class="nav-link {{ request()->routeIs('tareas.*') ? 'active' : '' }}" href="{{ route('tareas.index') }}">
                            <i class="fas fa-tasks me-2"></i>Tareas
                        </a>

                        @if(Auth::user()->isAdmin())
                        <div class="sidebar-heading">Gestión</div>
                        <a class="nav-link {{ request()->routeIs('empleados.*') ? 'active' : '' }}" href="{{ route('empleados.index') }}">
                            <i class="fas fa-users me-2"></i>Empleados
                        </a>

                        <a class="nav-link {{ request()->routeIs('clientes.*') ? 'active' : '' }}" href="{{ route('clientes.index') }}">
                            <i class="fas fa-user-tie me-2"></i>Clientes
                        </a>

                        {{-- Menú Desplegable para Clientes V2 --}}
                        <a class="nav-link d-flex justify-content-between align-items-center"
                            data-bs-toggle="collapse"
                            href="#menuClientesV2"
                            role="button"
                            aria-expanded="{{ request()->is('v2/*') ? 'true' : 'false' }}">
                            <span><i class="fas fa-user-tie me-2"></i>Clientes V2</span>
                            <i class="fas fa-chevron-down small"></i>
                        </a>

                        <div class="collapse {{ request()->is('v2/*') ? 'show' : '' }}" id="menuClientesV2">
                            <div class="bg-light border-start border-3 border-primary ms-2">
                                <a class="nav-link small {{ request()->routeIs('v2.clientes.index') ? 'active' : '' }}" href="{{ route('v2.clientes.index') }}">
                                    <i class="fas fa-code me-2"></i>CRUD JavaScript
                                </a>
                                <a class="nav-link small text-muted" href="#">
                                    <i class="fas fa-bolt me-2"></i>CRUD Livewire
                                </a>
                                <a class="nav-link small text-muted" href="#">
                                    <i class="fas fa-layer-group me-2"></i>CRUD Vue
                                </a>
                            </div>
                        </div>

                        <a class="nav-link {{ request()->routeIs('cuotas.*') ? 'active' : '' }}" href="{{ route('cuotas.index') }}">
                            <i class="fas fa-file-invoice-dollar me-2"></i>Cuotas
                        </a>
                        @endif
                    </nav>
                </aside>
                <main class="col-md-9 col-lg-10 p-4">
                    @yield('content')
                </main>
                @else
                {{-- SI NO ESTÁ LOGUEADO: Main 12 columnas (Centrado total) --}}
                <main class="col-12 p-4">
                    @yield('content')
                </main>
                @endauth

            </div>
        </div>
    </div>
</body>

</html>