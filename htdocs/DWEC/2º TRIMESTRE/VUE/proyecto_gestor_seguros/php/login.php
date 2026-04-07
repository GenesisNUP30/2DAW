<?php
// Permitir el acceso desde cualquier origen (CORS) y definir formato JSON
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Incluimos la conexión que creamos antes
include "conexion.php";

// Leemos los datos que vienen de Vue (estilo axios/fetch)
$datosRecibidos = json_decode(file_get_contents("php://input"));

// Inicializamos la respuesta
$respuesta = [
    "status" => false,
    "mensaje" => "Usuario o contraseña incorrectos",
    "usuario" => null
];

if (!empty($datosRecibidos->usuario) && !empty($datosRecibidos->password)) {
    $user = $conexion->real_escape_string($datosRecibidos->usuario);
    $pass = $conexion->real_escape_string($datosRecibidos->password);

    // Consulta para buscar al usuario
    $sql = "SELECT id, usuario, nombre FROM usuarios WHERE usuario = '$user' AND password = '$pass' LIMIT 1";
    $resultado = $conexion->query($sql);

    if ($resultado->num_rows > 0) {
        $datosUsuario = $resultado->fetch_assoc();
        
        $respuesta["status"] = true;
        $respuesta["mensaje"] = "Acceso concedido";
        $respuesta["usuario"] = [
            "id" => $datosUsuario['id'],
            "nombre" => $datosUsuario['nombre'],
            "login" => $datosUsuario['usuario']
        ];
    }
}

// Enviamos la respuesta a Vue
echo json_encode($respuesta);