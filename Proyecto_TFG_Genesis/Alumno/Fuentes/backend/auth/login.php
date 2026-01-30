<?php
// Permitir solicitudes desde React
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json; charset=UTF-8');

// Incluir conexión
require_once '../config/conexion.php';

// Conectar a la base de datos
$conexion = conectarDB();

// Obtener datos del cuerpo de la solicitud
$datos = json_decode(file_get_contents('php://input'), true);

// Validar datos
if (!isset($datos['username']) || !isset($datos['password'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Usuario y contraseña son obligatorios.'
    ]);
    exit;
}

$username = $datos['username'];
$password = $datos['password'];

// Buscar usuario en la BD
$stmt = $conexion->prepare("SELECT id, username, password, rol, activo FROM usuarios WHERE username = ?");
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Usuario no encontrado.'
    ]);
    exit;
}

$usuario = $result->fetch_assoc();

// Verificar si está activo
if (!$usuario['activo']) {
    echo json_encode([
        'success' => false,
        'message' => 'Usuario inactivo.'
    ]);
    exit;
}

// Verificar contraseña
if (password_verify($password, $usuario['password'])) {
    // Devuelve datos sin la contraseña
    unset($usuario['password']);
    echo json_encode([
        'success' => true,
        'user' => $usuario
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Contraseña incorrecta.'
    ]);
}

$stmt->close();
$conexion->close();
