<?php
include_once 'conexion.php';

if ($conexion->connect_error) {
    die(json_encode(["error" => "Error de conexión: " . $conexion->connect_error]));
}

$data = json_decode(file_get_contents("php://input"), true);

$codigo = $data['codigo'];

$sql = "DELETE FROM articulos WHERE codigo = '$codigo'";
$resultado = mysqli_query($conexion, $sql);

if ($resultado) {
    echo json_encode([
        "status" => "ok",
        "mensaje" => "Articulo eliminado correctamente"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "mensaje" => "Error al eliminar el articulo"
    ]);
}
