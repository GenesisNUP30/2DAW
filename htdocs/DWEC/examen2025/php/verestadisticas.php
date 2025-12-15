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

// $sql = "SELECT mascotas.nombre AS mascota_comun FROM mascotas INNER JOIN clientes ON mascotas.id_cliente = clientes.id WHERE clientes.id = (SELECT MAX(id) FROM clientes)";
// $resultado = mysqli_query($conexion, $sql);
// $datos['mascota_comun'] = $resultado->fetch_assoc()['mascota_comun'];

// $sql = "SELECT clientes.nombre AS cliente_con_mas_mascotas FROM mascotas INNER JOIN clientes ON mascotas.id_cliente = clientes.id WHERE clientes.id = (SELECT MAX(id) FROM clientes)";
// $resultado = mysqli_query($conexion, $sql);
// $datos['cliente_con_mas_mascotas'] = $resultado->fetch_assoc()['cliente_con_mas_mascotas'];

// Siempre devolvemos un ARRAY (vacío si no hay datos)
echo json_encode($datos, JSON_UNESCAPED_UNICODE);
?>