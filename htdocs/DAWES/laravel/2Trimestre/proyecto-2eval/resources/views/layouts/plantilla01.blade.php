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
      display: flex;
      flex-direction: column;
    }

    .sidebar a {
      color: #2c3e50;
      font-weight: 500;
      padding: 8px 12px;
      border-radius: 6px;
      transition: all 0.2s ease;
    }

    .nav-config {
      background: linear-gradient(135deg, #6b46c1, #553c9a);
      color: #fff !important;
      font-weight: 600;
      border-radius: 10px;
      padding: 12px 10px;
      transition: all 0.25s ease;
    }

    .nav-config:hover {
      background: linear-gradient(135deg, #553c9a, #44337a);
      transform: translateY(-2px);
    }


    /* Footer */
    .footer-app {
      background: #2c3e50;
      color: white;
      padding: 15px;
      margin-top: 30px;
      text-align: center;
    }

    body {
      transition: background-color 0.3s, color 0.3s;
    }

    body.claro {
      background-color: #f8fafc;
      color: #1a202c;
    }

    body.oscuro {
      --bg: #1f2937;
      --panel: #111827;
      --panel2: #1f2937;
      --text: #e5e7eb;
      --muted: #9ca3af;
      --border: #374151;
      --link: #9bc6fe;
      --hover: #374151;
    }

    /* Fondo general */
    body.oscuro {
      background-color: var(--bg) !important;
      color: var(--text) !important;
    }

    /* Textos generales */
    body.oscuro h1,
    body.oscuro h2,
    body.oscuro h3,
    body.oscuro h4,
    body.oscuro h5,
    body.oscuro h6,
    body.oscuro label,
    body.oscuro .form-label,
    body.oscuro p,
    body.oscuro span,
    body.oscuro div {
      color: var(--text);
    }

    /* Contenedores */
    body.oscuro .container,
    body.oscuro .container-fluid,
    body.oscuro .card,
    body.oscuro .col-md-10,
    body.oscuro .p-4 {
      background-color: var(--bg) !important;
    }

    /* Paneles (tablas, formularios, bloques) */
    body.oscuro .tabla-tareas,
    body.oscuro .card,
    body.oscuro .modal-content {
      background-color: var(--panel);
      color: var(--text);
    }

    /* Tablas */
    body.oscuro table {
      background-color: var(--panel);
      color: var(--text);
    }

    body.oscuro th {
      background-color: var(--panel2) !important;
      color: var(--text) !important;
    }

    body.oscuro td {
      color: var(--text);
    }

    body.oscuro tr:nth-child(even) {
      background-color: #0f172a;
    }

    body.oscuro tr:hover {
      background-color: #1e293b;
    }

    /* Inputs */
    body.oscuro input,
    body.oscuro select,
    body.oscuro textarea {
      background-color: var(--panel2);
      color: var(--text);
      border: 1px solid var(--border);
    }

    body.oscuro input::placeholder {
      color: var(--muted);
    }

    /* Enlaces reales (no botones ni menú) */
    body.oscuro a:not(.btn):not(.nav-link) {
      color: var(--link);
    }

    /* Menú lateral */
    body.oscuro .sidebar {
      background-color: var(--panel);
      border-right: 1px solid var(--border);
    }

    body.oscuro .sidebar a {
      color: var(--text);
    }

    body.oscuro .sidebar a:hover {
      background-color: var(--hover);
    }

    body.oscuro .alert-warning {
      background-color: #422006;
      border-color: #92400e;
      color: #fde68a;
    }

    body.oscuro .alert-warning .text-danger {
      color: #fca5a5 !important;
    }

    body.oscuro .tarea-datos,
    body.oscuro .detalle-tarea {
      background-color: var(--panel) !important;
      border: 1px solid var(--border);
      color: var(--text);
    }

    body.oscuro .form-control[readonly],
    body.oscuro input[type="file"] {
      background-color: #374151;
      color: #e5e7eb;
    }

    /* Filas */
    body.oscuro .campo {
      border-bottom: 1px solid var(--border);
    }

    body.oscuro .campo .label {
      color: #c7d2fe;
    }

    /* Títulos de sección */
    body.oscuro .detalle-tarea h2,
    body.oscuro .detalle-tarea h3 {
      color: #bfdbfe;
      border-color: var(--border);
    }

    /* Header y footer */
    body.oscuro .header-app,
    body.oscuro .footer-app {
      background-color: #020617;
      color: var(--text);
    }
  </style>

  @yield('estilos')
</head>

<body class="{{ $_SESSION['tema'] ?? 'claro' }}">
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

  <div class="container-fluid">
    <div class="row">

      <!-- MENÚ LATERAL -->
      <div class="col-md-2 sidebar">
        <ul class="nav flex-column flex-grow-1">

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

          @if(!empty($_SESSION['rol']))
          <div class="p-3 mt-auto">
            <a class="nav-link nav-config w-100 text-center" href="{{ miurl('configavanzada') }}">
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

  <!-- FOOTER -->
  <div class="footer-app">
    &copy; 2025 Mi Aplicación de Tareas
  </div>

  <!-- Scripts Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>