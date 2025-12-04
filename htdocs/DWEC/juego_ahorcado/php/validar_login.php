<?php
// Encabezado para indicar que la respuesta es JSON
header('Content-Type: application/json; charset=utf-8');

include 'conexion.php';

if ($conexion->connect_error) {
    die(json_encode(["error" => "Error de conexión: " . $conexion->connect_error]));
}

$usuario = $_GET['usuario'];
$password = $_GET['password'];

// Consulta SQL sin usar prepared statements
$sql = "SELECT * FROM usuarios WHERE usuario = '$usuario' AND password = '$password'";
$resultado = mysqli_query($conexion, $sql);

if ($resultado->num_rows > 0) {
    $usuario = $resultado->fetch_assoc();

    // Establecer variables de sesión
    $_SESSION['usuario'] = $usuario['usuario'];
    $_SESSION['rol'] = $usuario['rol'];
    
    echo json_encode([
        "status" => "success",
        "message" => "Bienvenido " . $usuario['usuario'],
        "rol" => $usuario['rol'] 
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Usuario o contraseña incorrectos."
    ]);
}
$conexion->close();
?>