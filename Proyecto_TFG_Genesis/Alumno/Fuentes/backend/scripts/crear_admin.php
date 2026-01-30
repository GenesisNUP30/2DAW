<?php
require_once '../config/conexion.php';

$conexion = conectarDB();

$username = 'admin';
$passwordPlano = 'admin123';
$rol = 'admin';

// Cifrar contraseña
$passwordCifrada = password_hash($passwordPlano, PASSWORD_DEFAULT);

// Insertar usuario
$stmt = $conexion->prepare("
    INSERT INTO usuarios (username, password, rol, activo)
    VALUES (?, ?, ?, 1)
");

$stmt->bind_param('sss', $username, $passwordCifrada, $rol);

if ($stmt->execute()) {
    echo "Usuario administrador creado correctamente";
} else {
    echo "Error al crear usuario: " . $stmt->error;
}

$stmt->close();
$conexion->close();
