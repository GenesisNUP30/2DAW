<?php
// Permitir el acceso desde cualquier origen (CORS) y definir formato JSON
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Incluimos la conexión que creamos antes
include "conexion.php";

// Leemos los datos que vienen de Vue (estilo axios/fetch)
$datos = json_decode(file_get_contents("php://input"));

// Inicializamos la respuesta
$res = ["status" => false, "mensaje" => "Credenciales incorrectas"];

if (!empty($datos->usuario) && !empty($datos->password)) {
    $user = $conexion->real_escape_string($datos->usuario);

    // Consulta para buscar al usuario
    $sql = "SELECT * FROM usuarios WHERE usuario = '$user' LIMIT 1";
    $result = $conexion->query($sql);

    if (!empty($datosRecibidos->usuario) && !empty($datosRecibidos->password)) {
        $user = $conexion->real_escape_string($datosRecibidos->usuario);
        $pass = $conexion->real_escape_string($datosRecibidos->password);

        // Consulta para buscar al usuario
        $sql = "SELECT id, usuario, nombre FROM usuarios WHERE usuario = '$user' AND password = '$pass' LIMIT 1";
        $resultado = $conexion->query($sql);

        if ($result->num_rows > 0) {
            $u = $result->fetch_assoc();
            // VERIFICAMOS EL HASH
            if (password_verify($datos->password, $u['password'])) {
                $res = [
                    "status" => true,
                    "mensaje" => "¡Hola de nuevo!",
                    "usuario" => ["id" => $u['id'], "nombre" => $u['nombre']]
                ];
            }
        }
    }
}
echo json_encode($res);
