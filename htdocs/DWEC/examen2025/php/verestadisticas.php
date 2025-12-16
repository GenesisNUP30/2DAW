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

$sql = "SELECT COUNT(*) AS total_clientes FROM clientes";
$resultado = mysqli_query($conexion, $sql);
$datos['total_clientes'] = $resultado->fetch_assoc()['total_clientes'];

$sql = "SELECT COUNT(*) AS total_mascotas FROM mascotas";
$resultado = mysqli_query($conexion, $sql);
$datos['total_mascotas'] = $resultado->fetch_assoc()['total_mascotas'];

$sql = "SELECT especie, COUNT(*) AS cantidad FROM mascotas GROUP BY especie ORDER BY cantidad DESC LIMIT 1";
$resultado = mysqli_query($conexion, $sql);
$fila = $resultado->fetch_assoc();
$datos['mascota_comun'] = $fila['especie'];
$datos['cantidad_mascota_comun'] = $fila['cantidad'];

$sql = "SELECT c.nombre AS cliente_nombre, COUNT(m.id) AS cantidad_mascotas FROM clientes c JOIN mascotas m ON c.id = m.id_cliente GROUP BY c.id, c.nombre ORDER BY cantidad_mascotas DESC LIMIT 1";
$resultado = mysqli_query($conexion, $sql);
$fila = $resultado->fetch_assoc();
$datos['cliente_con_mas_mascotas'] = $fila['cliente_nombre'];
$datos['cantidad_mascotas_cliente'] = $fila['cantidad_mascotas'] ?? 0;

// Siempre devolvemos un ARRAY (vacío si no hay datos)
echo json_encode($datos, JSON_UNESCAPED_UNICODE);
