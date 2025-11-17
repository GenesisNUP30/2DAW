<?php

$host = "localhost";
$user = "genesisnatalya";
$password = "jy*fD4@i3CWlgi4h";
$base_de_datos = "genesisnatalya";

// try {
//     $conexion = mysqli_connect($host, $user, $password, $base_de_datos);
//     mysqli_set_charset($conexion, 'utf8');
// } catch (Exception $ex) {
//     echo "Ha  fallado la conexión <br>";
//     echo $ex->getMessage();
//     exit;
// }

// Crear conexión
$conn = new mysqli($host, $usuario, $contraseña, $base_datos);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>