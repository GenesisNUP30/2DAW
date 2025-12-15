<?php
// Encabezado para indicar que la respuesta es JSON
header('Content-Type: application/json; charset=utf-8');

// Incluir el archivo de conexión
include 'conexion.php';

// Verificar conexión
if ($conexion->connect_error) {
    echo json_encode(["error" => "Error de conexión: " . $conexion->connect_error]);
    exit; // ← usar exit, no die (mejor para JSON)
}

$sql = "SELECT * FROM coches";
$resultado = $conexion->query($sql);

$coches = [];

if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        // Asegurar que 'vendido' sea entero (por si acaso)
        $fila['vendido'] = (int)$fila['vendido'];
        $coches[] = $fila;
    }
}
// Siempre devolvemos un ARRAY (vacío si no hay datos)
echo json_encode($coches, JSON_UNESCAPED_UNICODE);
?>