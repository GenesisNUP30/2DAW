<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

  <title> @yield('titulo') </title>
  @yield('estilos')
</head>

<body>
  <div class="container-fluid">
    <!-- ENCABEZADO -->
    <div class="row bg-primary text-white p-2">
      <div class="col-md-6">
        <h4>Mi Aplicación de Tareas</h4>
      </div>
      <div class="col-md-6 text-right">
        @if(!empty($_SESSION['logado']))
        Usuario: {{ $_SESSION['usuario'] }} |
        Rol: {{ $_SESSION['rol'] }} |
        Hora login: {{ $_SESSION['hora_logado'] }} |
        <a href="/DAWES/laravel/proyecto-1eval/public/logout" class="text-white">Cerrar sesión</a>
        @endif
      </div>
    </div>

    <div class="row mt-2">
      <!-- MENÚ LATERAL -->
      <div class="col-md-2 bg-light p-2">
        <ul class="nav flex-column">
          <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">Inicio</a></li>
          @if(!empty($_SESSION['rol']) && $_SESSION['rol'] == 'administrador')
          <li class="nav-item"><a class="nav-link" href="{{ url('alta') }}">Alta de tarea</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Modificar tarea</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Eliminar tarea</a></li>
          @elseif(!empty($_SESSION['rol']) && $_SESSION['rol'] == 'operario')
          <li class="nav-item"><a class="nav-link" href="#">Completar tarea</a></li>
          @endif
        </ul>
      </div>

      <!-- CUERPO PRINCIPAL -->
      <div class="col-md-10">
        @yield('cuerpo')
      </div>
    </div>

    <!-- PIE DE PÁGINA -->
    <div class="row bg-secondary text-white text-center mt-3 p-2">
      <div class="col-12">
        &copy; 2025 Mi Aplicación de Tareas
      </div>
    </div>
  </div>
  <!-- Scripts de Bootstrap -->
  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>