<?php
header("Content-type: application/json");

include "conexion.php";

// Verificar conexión
if ($conexion->connect_error) {
    die(json_encode(["error" => "Error de conexión: " . $conn->connect_error]));
}

$texto = $_GET["codigopostal"];

$sql = "SELECT codigo_postal, nombre FROM poblaciones WHERE codigo_postal LIKE '$texto%'";
$resultado = mysqli_query($conexion, $sql);

$poblaciones = [];

if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $poblaciones[] = $fila;
    }
    // Devolver los datos como JSON
    echo json_encode($poblaciones, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
} else {
    echo json_encode(["mensaje" => "No hay registros para el código postal proporcionado."]);
}

// Cerrar conexión
mysqli_close($conexion);
?>