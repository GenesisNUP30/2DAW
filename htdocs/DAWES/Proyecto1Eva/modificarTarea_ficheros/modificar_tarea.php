<?php
require_once __DIR__.'/../conexion.php';
// Obtenemos el ID de la tarea de 2 formas posibles
// POST si estamos modificando una tarea ya creada
if (isset($_POST['idTarea'])) {
    $idTarea = $_POST['idTarea'];
} elseif (isset($_GET['id'])) {
    // GET si estamos buscando una tarea el ID que introdujo el usuario en el formulario
    $idTarea = $_GET['id'];
} else {
    $idTarea = 0;
}

if ($idTarea <= 0) {

?>
    <form method="get">
        <label for="idTarea">Introduce el ID de la tarea a modificar:</label>
        <input type="number" name="id" id="idTarea" required>
        <button type="submit">Buscar tarea</button>
        <br><br>
        <a href="../index.php">Cancelar</a>
    </form>
<?php
    exit;
}

// BUSCAMOS EN LA BASE DE DATOS LA TAREA QUE COINCIDA CON EL ID QUE LE HEMOS PASADO
$sql = "SELECT * FROM tareas WHERE id = $idTarea";
$resultado = mysqli_query($conexion, $sql);
if (!$resultado || mysqli_num_rows($resultado) === 0) {
    echo "No se encontró la tarea con ID $idTarea";
    exit;
}

$tareaExistente = mysqli_fetch_assoc($resultado);

// Inicializar variables con los datos existentes
$nifCif = $tareaExistente['nif_cif'];
$personaNombre = $tareaExistente['persona_contacto'];
$telefono = $tareaExistente['telefono'];
$descripcionTarea = $tareaExistente['descripcion'];
$correo = $tareaExistente['email'];
$direccionTarea = $tareaExistente['direccion'];
$poblacion = $tareaExistente['poblacion'];
$codigoPostal = $tareaExistente['codigo_postal'];
$provincia = $tareaExistente['provincia'];
$estadoTarea = $tareaExistente['estado'];
$operarioEncargado = $tareaExistente['operario_encargado'];
$fechaRealizacion = $tareaExistente['fecha_realizacion'];
$anotacionesAnteriores = $tareaExistente['anotaciones_anteriores'];
$anotacionesPosteriores = $tareaExistente['anotaciones_posteriores'];
$errores = [];

//Llamada al archivo de las funciones
require_once __DIR__ . '/../funciones.php';

if ($_POST) {
    // Recoger datos del POST (igual que en alta)
    $nifCif = trim($_POST['nifCif']);
    $personaNombre = trim($_POST['personaNombre']);
    $telefono = trim($_POST['telefono']);
    $descripcionTarea = trim($_POST['descripcionTarea']);
    $correo = trim($_POST['correo']);
    $direccionTarea = trim($_POST['direccionTarea']);
    $poblacion = trim($_POST['poblacion']);
    $codigoPostal = trim($_POST['codigoPostal']);
    $provincia = trim($_POST['provincia']);
    $estadoTarea = trim($_POST['estadoTarea']);
    $operarioEncargado = trim($_POST['operarioEncargado']);
    $fechaRealizacion = trim($_POST['fechaRealizacion']);
    $anotacionesAnteriores = trim($_POST['anotacionesAnteriores']);
    $anotacionesPosteriores = trim($_POST['anotacionesPosteriores']);

    // Validaciones 
    if ($nifCif == "") {
        $errores[] = "Debe introducir el NIF/CIF<br>";
    } else {
        $resultado = validarNif($nifCif);
        if ($resultado !== true) {
            $errores[] = $resultado . "<br>";
        }
    }

    if ($personaNombre === "") {
        $errores[] = "Debe introducir el nombre de la persona";
    }

    if ($descripcionTarea === "") {
        $errores[] = "Debe introducir la descripción de la tarea<br>";
    }

    if ($correo === "") {
        $errores[] = "Debe introducir el correo<br>";
    } else if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El correo introducido no es válido";
    }

    if ($telefono == "") {
        $errores[] = "Debe introducir el teléfono";
    } else {
        $resultado = telefonoValido($telefono);
        if ($resultado !== true) {
            $errores[] = $resultado . "<br>";
        }
        
    }

    if ($codigoPostal != "" && !preg_match("/^[0-9]{5}$/", $codigoPostal)) {
        $errores[] = "El código postal debe tener 5 números";
    }

    $fechaActual = date('Y-m-d');
    if ($fechaRealizacion == "") {
        $errores[] = "Debe introducir la fecha de realización de la tarea";
    } else {
        if ($fechaRealizacion <= $fechaActual) {
            $errores[] = "La fecha de realización debe ser posterior a la fecha actual";
        }
    }

    // Redirección
    if (!empty($errores)) {
        include 'modificar_tarea_form.php';
        exit;
    } else {
        // Actualizar en la BD
        $sqlActualizado = "UPDATE tareas SET
            nif_cif='$nifCif', persona_contacto='$personaNombre', telefono='$telefono',
            descripcion='$descripcionTarea', email='$correo', direccion='$direccionTarea',
            poblacion='$poblacion', codigo_postal='$codigoPostal', provincia='$provincia',
            estado='$estadoTarea', operario_encargado='$operarioEncargado',
            fecha_realizacion='$fechaRealizacion', anotaciones_anteriores='$anotacionesAnteriores',
            anotaciones_posteriores='$anotacionesPosteriores'
            WHERE id=$idTarea";

        if (mysqli_query($conexion, $sqlActualizado)) {
            include 'modificar_tarea_procesado.php';
        } else {
            $errores[] = "Error al actualizar: " . mysqli_error($conexion);
            include 'modificar_tarea_form.php';
        }
        exit;
    }
} else {
    include 'modificar_tarea_form.php';
}
?>