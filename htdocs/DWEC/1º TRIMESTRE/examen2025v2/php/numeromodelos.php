<?php
// Encabezado para indicar que la respuesta es JSON
header('Content-Type: application/json');

//Incluir el archivo de conexión
include 'conexion.php';

//Verificar conexión
if ($conexion->connect_error) {
    die(json_encode(["error" => "Error de conexión: " . $conexion->connect_error]));
}

$id_marca = $_GET['id_marca'];
$sql = "SELECT COUNT(*) FROM modelos WHERE id_marca = $id_marca";
$resultado = $conexion->query($sql);

$numero_modelos = 0;

if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $numero_modelos = $fila['COUNT(*)'];
    }
    // Devolver los datos como JSON
    echo json_encode($numero_modelos, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    
}

// Cerrar conexión
$conexion->close();
?>