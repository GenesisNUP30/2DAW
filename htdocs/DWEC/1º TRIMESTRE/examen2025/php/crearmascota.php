<?php
// Encabezado para indicar que la respuesta es JSON
header('Content-Type: application/json');

//Incluir el archivo de conexión
include 'conexion.php';

//Verificar conexión
if ($conexion->connect_error) {
    die(json_encode(["error" => "Error de conexión: " . $conexion->connect_error]));
}

$nombre = $_GET["nombre"];
$especie = $_GET["especie"];
$raza = $_GET["raza"];
$fechanacimiento = $_GET["fechanacimiento"];
$id_cliente = $_GET["id_cliente"];

$sql = "INSERT INTO mascotas (nombre, especie, raza, fecha_nacimiento, id_cliente) VALUES ('$nombre', '$especie', '$raza', '$fechanacimiento', '$id_cliente')";
$resultado = mysqli_query($conexion, $sql);

if ($resultado) {
    echo json_encode([
        "status" => "success",
        "mensaje" => "Mascota creada correctamente."
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "mensaje" => "Error al crear la mascota."
    ]);
}

// Cerrar conexión
mysqli_close($conexion);
?>