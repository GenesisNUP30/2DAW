<?php
// Encabezado para indicar que la respuesta es JSON
header('Content-Type: application/json; charset=utf-8');

//Incluir el archivo de conexión
include 'conexion.php'; //o require 'conexion.php';

//Verificar conexión
if ($conexion->connect_error) {
    die(json_encode(["error" => "Error de conexión: " . $conexion->connect_error]));
}

$id_nota = $_GET['id_nota'];

//Consulta sql
$sql = "SELECT * FROM notas WHERE id=". $id_nota;
$resultado = mysqli_query($conexion, $sql);

$notas = [];

if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $notas[] = $fila;
    }
    // Devolver los datos como JSON
    echo json_encode($notas, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    
} else {
    echo json_encode(["mensaje" => "No hay registros en la tabla notas."]);
}

// Cerrar conexión
$conexion->close();
