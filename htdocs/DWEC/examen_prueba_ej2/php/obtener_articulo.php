<?php
header("Content-type: application/json");

include "conexion.php";

// Verificar conexión
if ($conexion->connect_error) {
    die(json_encode(["error" => "Error de conexión: " . $conn->connect_error]));
}

$codigo = $_GET["codigo"];

$sql = "SELECT codigo, descripcion, precio FROM articulos WHERE codigo = '$codigo'";
$resultado = mysqli_query($conexion, $sql);

$articulo = [];

if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $articulo[] = $fila;
    }
    // Devolver los datos como JSON
    echo json_encode($articulo, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
} 
else {
    echo json_encode([
        "status" => "error",
        "mensaje" => "No hay registros para el código proporcionado."]);
}

// Cerrar conexión
mysqli_close($conexion);
?>