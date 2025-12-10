<?php
// Indicamos que la respuesta será en formato JSON
header('Content-Type: application/json');

// Incluir el archivo de conexión
include "conexion.php";

if ($conexion->connect_error) {
    die(json_encode(["error" => "No se pudo conectar a la base de datos: " . mysqli_connect_error()]));
}

// Consulta sql para obtener todos los empleados
$sql = "SELECT * from empleados";
$resultado = $conexion->query($sql);

$empleados = [];

if ($resultado->num_rows > 0) {
    // Si la consulta tuvo éxito, obtenemos los datos
    while ($fila = $resultado->fetch_assoc()) {
        $empleados[] = $fila;
    }
    // Devolvemos los datos en formato JSON
    echo json_encode($empleados, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
} else {
    // Si la consulta falló, devolvemos un mensaje de error
    die(json_encode(["mensaje" => "No se encontraron empleados en la tabla."]));
}


// Cerrar conexión
$conexion->close();
