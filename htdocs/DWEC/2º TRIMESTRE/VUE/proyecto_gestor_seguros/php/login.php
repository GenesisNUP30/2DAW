<?php
// Permitir el acceso desde cualquier origen (CORS) y definir formato JSON
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Incluimos la conexión que creamos antes
include "conexion.php";

// Leemos los datos que vienen de Vue (estilo axios/fetch)
$json = file_get_contents("php://input");
$datos = json_decode($json);

// Inicializamos la respuesta
$res = ["status" => false, "mensaje" => "Credenciales incorrectas"];

if ($datos && !empty($datos->usuario) && !empty($datos->password)) {
    $user = $conexion->real_escape_string($datos->usuario);

    // Consulta para buscar al usuario
    $sql = "SELECT * FROM gestor_usuarios WHERE usuario = '$user' LIMIT 1";
    $result = $conexion->query($sql);

    if ($result && $result->num_rows > 0) {
        $u = $result->fetch_assoc();

        // Verificamos si la contraseña coincide con el hash
        if ($datos->password === $u['password']) {
            $res = [
                "status" => true,
                "mensaje" => "¡Hola de nuevo!",
                "usuario" => [
                    "id" => $u['id'],
                    "nombre" => $u['nombre']
                ]
            ];
        } else {
            $res["mensaje"] = "La contraseña no coincide en el sistema";
        }
    }
}
echo json_encode($res);
