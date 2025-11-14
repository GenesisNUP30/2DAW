<?php
require_once 'conexion.php';


if (!isset($_GET['id'])) {
?>
    <form method="get">
        <label for="idTarea">Introduce el ID de la tarea a eliminar:</label>
        <input type="number" name="id" id="idTarea" required>
        <button type="submit">Buscar tarea</button>
    </form>
<?php
    exit;
}

// Si llegamos aquí, hay un ID
$idTarea = $_GET['id'];

// La tarea se carga desde la base de datos
$sql = "SELECT * FROM tareas WHERE id = $idTarea";
$resultado = mysqli_query($conexion, $sql);

if (!$resultado || mysqli_num_rows($resultado) === 0) {
    echo "No se encontró la tarea con ID $idTarea";
    exit;
}

$tareaExistente = mysqli_fetch_assoc($resultado);

// Función para mostrar fecha en dd/mm/yyyy
function fechaParaMostrar($fechaYMD) {
    // Esto habra que cambiarlo despues porque el campo de fechaRealizacion es type date y no permite escribir otra cosa que no sea una fecha valida 
    if (!$fechaYMD) return '';
    $partes = explode('-', $fechaYMD);
    return $partes[2] . '/' . $partes[1] . '/' . $partes[0];
}

// Pasar datos a la vista
$nifCif = $tareaExistente['nif_cif'];
$personaNombre = $tareaExistente['persona_contacto'];
$descripcionTarea = $tareaExistente['descripcion'];
$fechaRealizacion = fechaParaMostrar($tareaExistente['fecha_realizacion']);
$estadoTarea = $tareaExistente['estado'];
$idTarea = $tareaExistente['id'];

include 'eliminar_tarea_form.php';
?>