<?php
header('Content-Type: application/json');

include 'conexion.php';

if ($conexion->connect_error) {
    die(json_encode(["error" => "Error de conexión: " . $conexion->connect_error]));
}

$cp = $_GET['cp'];
$nh = $_GET['habitantes'];

$sql = "UPDATE poblaciones SET habitantes = '$nh' WHERE codigo_postal = '$cp'";
$resultado = mysqli_query($conexion, $sql);

if($resultado){
    echo json_encode([
        "status" => "success",
        "mensaje" => "Datos actualizados correctamente",
        "data" => [
            "habitantes" => $nh
        ]
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "mensaje" => "Error al actualizar datos"
    ]);
}
?>

