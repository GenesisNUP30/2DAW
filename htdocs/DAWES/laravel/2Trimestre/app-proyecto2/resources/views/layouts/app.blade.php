<!doctype html>
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
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
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

            <div class="container-fluid">
                <div class="row">

                    <!-- MENÚ LATERAL -->
                    <div class="col-md-2 sidebar">
                        <ul class="nav flex-column flex-grow-1">

                            <li class="nav-item">
                                <a class="nav-link" href="route('/')">
                                    <i class="fas fa-home"></i> Inicio
                                </a>
                            </li>

                            @if (Auth::user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link" href="">
                                    <i class="fas fa-plus-circle"></i> Crear nueva tarea
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="">
                                    <i class="fas fa-users"></i> Ver lista de usuarios
                                </a>
                            </li>

                            @elseif (Auth::user()->isEmpleado())
                            <li class="nav-item">
                                <a class="nav-link" href="">
                                    <i class="fas fa-user-edit"></i> Editar mi usuario
                                </a>
                            </li>
                            @endif

                            @if(Auth::user())
                            <div class="p-3 mt-auto">
                                <a class="nav-link nav-config w-100 text-center" href="">
                                    <i class="fas fa-cogs me-2"></i> Configuración
                                </a>
                            </div>
                            @endif


                        </ul>
                    </div>

                    <!-- CONTENIDO PRINCIPAL -->
                    <div class="col-md-10 p-4">
                        @yield('cuerpo')
                    </div>

                </div>
            </div>
            @yield('content')
        </main>
    </div>
</body>

</html>