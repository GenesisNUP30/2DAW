<?php

include 'conexion.php';

if ($conexion->connect_error) {
    die(json_encode(["error" => "Error de conexión: " . $conexion->connect_error]));
}

// Consulta SQL
$sql = "SELECT * FROM alumnos";
$resultado = $conexion->query($sql);
$cadena = "";

if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $cadena = $cadena. "<li>".htmlspecialchars($fila['nombre'])."</li>";
    }
} else {
    echo "<tr><td colspan='4'>No hay registros en la tabla alumnos.</td></tr>";
}

echo $cadena;
$conexion->close();
?>