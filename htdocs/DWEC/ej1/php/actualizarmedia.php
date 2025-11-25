<?php
// Encabezado para indicar que la respuesta es JSON
header('Content-Type: application/json; charset=utf-8');

// Incluir el archivo de conexión
include 'conexion.php';

// Verificar conexión
if ($conexion->connect_error) {
    die(json_encode(["error" => "Error de conexión: " . $conexion->connect_error]));
}

$codigo_alumno = $_GET['codigo_alumno'];

// Calcular la media
$sql_media = "SELECT AVG(nota) AS media FROM notas WHERE codigo_alumno = $codigo_alumno";
$resultado_media = mysqli_query($conexion, $sql_media);

if ($resultado_media && mysqli_num_rows($resultado_media) > 0) {
    $fila = mysqli_fetch_assoc($resultado_media);
    $media = $fila['media'];

    // Si no hay notas, la media será NULL → ponemos 0
    if ($media === null) {
        $media = 0;
    } else {
        $media = round($media, 2);
    }

    // Actualizar la nota del alumno
    $sql_actualizar = "UPDATE alumnos SET nota = $media WHERE codigo = $codigo_alumno";
    if(mysqli_query($conexion, $sql_actualizar)) {
        echo json_encode([
            "status" => "success",
            "message" => "Nota actualizada correctamente",
            "data" => [
                "media" => $media
            ]
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => mysqli_error($conexion)
        ]);
    }
}
// Cerrar conexión
$conexion->close();

?>