{{-- resources/views/layouts/nav.blade.php --}}
<nav class="nav flex-column">
    <ul class="nav flex-column">

        <li class="nav-item">
            <a class="nav-link" href="{{ route('tareas.index') }}">
                <i class="fas fa-home"></i> Inicio
            </a>
        </li>

        @if(Auth::check() && Auth::user()->isAdmin())
        <li class="nav-item">
            <a class="nav-link" href="{{ route('tareas.create') }}">
                <i class="fas fa-plus-circle"></i> Crear nueva tarea
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('usuarios.index') }}">
                <i class="fas fa-users"></i> Ver lista de usuarios
            </a>
        </li>
        @elseif(Auth::check() && Auth::user()->isEmpleado())
        <li class="nav-item">
            <a class="nav-link" href="{{ route('perfil') }}">
                <i class="fas fa-user-edit"></i> Editar mi usuario
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

        @auth
        <li class="nav-item mt-auto">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-link nav-link p-0 text-danger">
                    <i class="fas fa-sign-out-alt"></i> Cerrar sesión
                </button>
            </form>
        </li>
        @endauth

    </ul>
</nav>