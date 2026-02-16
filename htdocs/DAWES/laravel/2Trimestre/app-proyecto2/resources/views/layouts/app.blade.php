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
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">


    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                        @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @endif

                        @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                        @endif
                        @else
                        <!-- Información del usuario en la navbar -->
                        <li class="nav-item d-none d-md-flex align-items-center me-3">
                            <span class="text-muted">Tipo:
                                <span>
                                    @if(Auth::user()->isAdmin())
                                    <i class="fas fa-user-shield"></i> Administrador
                                    @else
                                    <i class="fas fa-hard-hat"></i> Operario
                                    @endif
                                </span> |
                                <i class="far fa-clock ms-1"></i>
                                Última sesión: {{ Auth::user()->ultimaSesion() }}
                            </span>
                        </li>
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                <i class="fas fa-user me-1"></i>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('perfil') }}">
                                    <i class="fas fa-user-edit"></i> Editar mi perfil
                                </a>

                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i>{{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="row">
                <aside class="col-md-3 col-lg-2 bg-light">

                    <nav class="nav flex-column">
                        <ul class="nav flex-column">
                            @if(Auth::check())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('tareas.index') }}">
                                    <i class="fas fa-home"></i> Inicio
                                </a>
                            </li>
                            @endif

                            @if(Auth::check() && Auth::user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('tareas.create') }}">
                                    <i class="fas fa-plus-circle"></i> Crear nueva tarea
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('empleados.index') }}">
                                    <i class="fas fa-users"></i> Ver lista de empleados
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('clientes.index') }}">
                                    <i class="fas fa-users"></i> Ver lista de clientes
                                </a>
                            </li>

                            @endif

                            @if(Auth::user())
                            <div class="nav-item">
                                <a class="nav-link" href="">
                                    <i class="fas fa-cogs me-2"></i> Configuración
                                </a>
                            </div>
                            @endif

                            <!-- @auth
                            <li class="nav-item mt-auto">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-link nav-link p-0 text-danger">
                                        <i class="fas fa-sign-out-alt"></i> Cerrar sesión
                                    </button>
                                </form>
                            </li>
                            @endauth -->
                        </ul>
                    </nav>
                </aside>
                <section class="col-md-9 col-lg-9">
                    @yield('content')
                </section>

            </div>
        </main>
    </div>
</body>

</html>