<?php
// Encabezado para indicar que la respuesta es JSON
header('Content-Type: application/json; charset=utf-8');

//Incluir el archivo de conexión
include 'conexion.php';

//Verificar conexión
if ($conexion->connect_error) {
    die(json_encode(["error" => "Error de conexión: " . $conexion->connect_error]));
}

$datos = [];

$sql = "SELECT COUNT(*) AS total FROM coches";
$resultado = mysqli_query($conexion, $sql);
$datos['total_coches'] = $resultado->fetch_assoc()['total'];


$sql = "SELECT SUM(precio) AS suma FROM coches";
$resultado = mysqli_query($conexion, $sql);
$datos['suma_precios'] = $resultado->fetch_assoc()['suma'];

$sql = "SELECT AVG(precio) AS media FROM coches";
$resultado = mysqli_query($conexion, $sql);
$datos['media_precio'] = $resultado->fetch_assoc()['media']; 


$sql = "SELECT COUNT(*) AS vendidos FROM coches WHERE vendido = 1";
$resultado = mysqli_query($conexion, $sql);
$datos['coches_vendidos'] = $resultado->fetch_assoc()['vendidos'];

// Siempre devolvemos un ARRAY (vacío si no hay datos)
echo json_encode($datos, JSON_UNESCAPED_UNICODE);
?>