<?php
header('Content-Type: application/json');

include 'conexion.php';

if ($conexion->connect_error) {
    die(json_encode(["error" => "Error de conexión: " . $conexion->connect_error]));
}

$codigo = $_GET['codigo'];
$descripcion = $_GET['descripcion'];
$cantidad = $_GET['cantidad'];
$precio = $_GET['precio'];

$sql = "INSERT INTO articulos (descripcion, cantidad, precio) VALUES ('$descripcion', '$cantidad', '$precio')";
$resultado = mysqli_query($conexion, $sql);

if ($resultado) {
    echo json_encode([
        "status" => "success",
        "mensaje" => "Articulo insertado correctamente.",
        "data" => [
            "codigo" => $codigo,
            "descripcion" => $descripcion,
            "cantidad" => $cantidad,
            "precio" => $precio
        ]
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "mensaje" => "Error al insertar el articulo."
    ]);
}
// Cerrar conexión
mysqli_close($conexion);
?>