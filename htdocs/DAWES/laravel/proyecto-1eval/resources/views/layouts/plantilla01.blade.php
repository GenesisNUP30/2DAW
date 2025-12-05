<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap 5 -->
  <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

  <!-- Font Awesome -->
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <title>@yield('titulo')</title>

  <!-- Estilos generales -->
  <style>
    body {
      background: #f4f5f7;
      font-family: "Segoe UI", sans-serif;
    }

    /* Header */
    .header-app {
      background: #2c3e50;
      color: white;
      padding: 12px 20px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);

    }

    /* Menú lateral */
    .sidebar {
      background: #ffffff;
      min-height: 100vh;
      border-right: 1px solid #e0e0e0;
      padding-top: 20px;
      /* margin-left: 20px; */
    }

    .sidebar a {
      color: #2c3e50;
      font-weight: 500;
      padding: 8px 12px;
      border-radius: 6px;
      transition: all 0.2s ease;
    }

    .sidebar a:hover {
      background: #eef1f5;
    }

    /* Footer */
    .footer-app {
      background: #2c3e50;
      color: white;
      padding: 15px;
      margin-top: 30px;
      text-align: center;
    }
  </style>

  @yield('estilos')
</head>

<body>

  <!-- HEADER
  <div class="header-app d-flex justify-content-between align-items-center">
    <h4 class="m-0">
      <i class="fas fa-clipboard-check"></i> Gestor de tareas
    </h4>

    @if(!empty($_SESSION['logado']))
    <div>
      <i class="fas fa-user"></i> {{ $_SESSION['usuario'] }} |
      <i class="fas fa-id-badge"></i> {{ $_SESSION['rol'] }} |
      <i class="far fa-clock"></i> {{ \App\Models\Funciones::formatearFechaHora($_SESSION['hora_logado']) }} |
      <a href="{{ miurl('logout') }}" class="text-white text-decoration-none fw-bold">
        <i class="fas fa-sign-out-alt"></i> Cerrar sesión
      </a>
    </div>
    @endif
  </div> -->

  <div class="container-fluid">
    <div class="row">

      <!-- MENÚ LATERAL
      <div class="col-md-2 sidebar">
        <ul class="nav flex-column">

          <li class="nav-item">
            <a class="nav-link" href="{{ miurl('/') }}">
              <i class="fas fa-home"></i> Inicio
            </a>
          </li>

          @if(!empty($_SESSION['rol']) && $_SESSION['rol'] == 'administrador')
          <li class="nav-item">
            <a class="nav-link" href="{{ miurl('alta') }}">
              <i class="fas fa-plus-circle"></i> Crear nueva tarea
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="{{ miurl('listarusuarios') }}">
              <i class="fas fa-users"></i> Ver lista de usuarios
            </a>
          </li>

          @elseif(!empty($_SESSION['rol']) && $_SESSION['rol'] == 'operario')
          <li class="nav-item">
            <a class="nav-link" href="{{ miurl('editarusuario/' . $_SESSION['id']) }}">
              <i class="fas fa-user-edit"></i> Editar mi usuario
            </a>
          </li>
          @endif

        </ul>
      </div> -->

      <!-- CONTENIDO PRINCIPAL -->
      <div class="col-md-10 p-4">
        @yield('cuerpo')
      </div>

      <!-- MENÚ LATERAL -->
      <div class="col-md-2 sidebar">
        <ul class="nav flex-column">

          <li class="nav-item">
            <a class="nav-link" href="{{ miurl('/') }}">
              <i class="fas fa-home"></i> Inicio
            </a>
          </li>

          @if(!empty($_SESSION['rol']) && $_SESSION['rol'] == 'administrador')
          <li class="nav-item">
            <a class="nav-link" href="{{ miurl('alta') }}">
              <i class="fas fa-plus-circle"></i> Crear nueva tarea
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="{{ miurl('listarusuarios') }}">
              <i class="fas fa-users"></i> Ver lista de usuarios
            </a>
          </li>

          @elseif(!empty($_SESSION['rol']) && $_SESSION['rol'] == 'operario')
          <li class="nav-item">
            <a class="nav-link" href="{{ miurl('editarusuario/' . $_SESSION['id']) }}">
              <i class="fas fa-user-edit"></i> Editar mi usuario
            </a>
          </li>
          @endif

        </ul>
      </div>

    </div>
  </div>

  <!-- FOOTER -->
  <div class="footer-app">
    &copy; 2025 Mi Aplicación de Tareas
  </div>

  <!-- HEADER -->
  <div class="header-app d-flex justify-content-between align-items-center">
    <h4 class="m-0">
      <i class="fas fa-clipboard-check"></i> Gestor de tareas
    </h4>

    @if(!empty($_SESSION['logado']))
    <div>
      <i class="fas fa-user"></i> {{ $_SESSION['usuario'] }} |
      <i class="fas fa-id-badge"></i> {{ $_SESSION['rol'] }} |
      <i class="far fa-clock"></i> {{ \App\Models\Funciones::formatearFechaHora($_SESSION['hora_logado']) }} |
      <a href="{{ miurl('logout') }}" class="text-white text-decoration-none fw-bold">
        <i class="fas fa-sign-out-alt"></i> Cerrar sesión
      </a>
    </div>
    @endif
  </div>

  <!-- Scripts Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>