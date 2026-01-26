<?php
include_once 'conexion.php';

if ($conexion->connect_error) {
    die(json_encode(["error" => "Error de conexión: " . $conexion->connect_error]));
}

// Recibir el JSON
$data = json_decode(file_get_contents("php://input"), true);

$codigo = $data['codigo'];
$descripcion = $data['descripcion'];
$cantidad = $data['cantidad'];
$precio = $data['precio'];


$sql = "INSERT INTO articulos (codigo, descripcion, cantidad, precio) VALUES ('$codigo', '$descripcion', '$cantidad', '$precio')";

$resultado = mysqli_query($conexion, $sql);
if ($resultado) {
    echo json_encode(["status" => "ok", "mensaje" => "Articulo añadido correctamente"]);
} else {
    echo json_encode(["status" => "error", "mensaje" => "Error al añadir el articulo"]);
}
