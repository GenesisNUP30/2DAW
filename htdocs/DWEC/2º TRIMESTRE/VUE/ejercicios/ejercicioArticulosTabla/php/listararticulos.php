<?php
include_once 'conexion.php';

if ($conexion->connect_error) {
    die(json_encode(["error" => "Error de conexión: " . $conexion->connect_error]));
}

// $resultado = $conexion->query("SELECT * FROM articulos");
// $articulos = [];

// while ($fila = $resultado->fetch_assoc()) {
//     $articulos[] = $fila;
// }

$sql = "SELECT * FROM articulos";
$resultado = mysqli_query($conexion, $sql);

$articulos = [];

if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $articulos[] = $fila;
    }
    // Devolver los datos como JSON
    echo json_encode($articulos, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}

// echo json_encode($articulos, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

?>