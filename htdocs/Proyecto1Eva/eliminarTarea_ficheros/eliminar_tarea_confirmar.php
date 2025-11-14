<?php
require_once 'conexion.php';

if ($_POST && isset($_POST['confirmar']) && $_POST['confirmar'] === 'si') {
    $idTarea = isset($_POST['id_tarea']) ? $_POST['id_tarea'] : null;

    if ($idTarea) {
        // Ejecutar eliminación en la base de datos
        $sqlDelete = "DELETE FROM tareas WHERE id = $idTarea";
        if (mysqli_query($conexion, $sqlDelete)) {
            $mensaje = "La tarea con ID $idTarea ha sido eliminada correctamente.";
        } else {
            $mensaje = "Error al eliminar la tarea: " . mysqli_error($conexion);
        }
    } else {
        $mensaje = "Error: ID de tarea no válido.";
    }
} else {
    $mensaje = "Operación de eliminación cancelada.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultado de Eliminación</title>
</head>
<body>
    <h2>Resultado</h2>
    <p><?= htmlspecialchars($mensaje) ?></p>
    <br>
    <!-- <a href="index.php">Volver al inicio</a> -->
</body>
</html>
