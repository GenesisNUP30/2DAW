<?php

$host = $_POST['host'] ?? '';
$db   = $_POST['bd'] ?? '';
$user = $_POST['user'] ?? '';
$pass = $_POST['pass'] ?? '';
$adminUser  = $_POST['admin_user'] ?? '';
$adminPass  = $_POST['admin_pass'] ?? '';

function mostrarError($mensaje)
{
    echo "<!DOCTYPE html>
    <html lang='es'>
    <head>
    <meta charset='UTF-8'>
    <title>Error de Instalación</title>
    <style>
        body { font-family:sans-serif; background:#eee; padding:40px; text-align:center; }
        .box { background:#fee2e2; color:#b91c1c; padding:30px; max-width:500px; margin:auto; border-radius:8px; }
        a { display:inline-block; margin-top:20px; padding:10px 20px; background:#2563eb; color:white; text-decoration:none; border-radius:6px; }
        a:hover { background:#1e40af; }
    </style>
    </head>
    <body>
        <div class='box'>
            $mensaje
            <br><a href='index.php'>Volver</a>
        </div>
    </div>
    </body>
    </html>";
    exit;
}

// Comprobar que no existe el archivo de configuración
if (file_exists(__DIR__ . '/../app/config.php')) {
    mostrarError("La aplicación ya parece estar instalada. El archivo <strong>config.php</strong> ya existe.");
}

// Comprobar que los campos están completos
if (!$host || !$db || !$user) {
    mostrarError("Los campos de conexión a la base de datos son obligatorios.");
}

$config = "<?php
return [
    'host' => '$host',
    'db' => '$db',
    'user' => '$user',
    'pass' => '$pass',
];
";

if (!file_put_contents(__DIR__ . '/../app/config.php', $config)) {
    mostrarError("No se pudo crear el archivo de configuración <strong>config.php</strong>.");
}

// Conectar a la base de datos
$conexion = new mysqli($host, $user, $pass, $db);
if ($conexion->connect_error) {
    mostrarError("No se pudo conectar a la base de datos: " . $conexion->connect_error . ". Compruebe que los datos de conexión son correctos.");
}

// Verificar que podemos crear tablas antes de borrar nada
$testTable = "CREATE TABLE IF NOT EXISTS test_instalacion (id INT PRIMARY KEY)";
if (!$conexion->query($testTable)) {
    mostrarError("No se pueden crear tablas en esta base de datos. Compruebe permisos.");
}
$conexion->query("DROP TABLE test_instalacion");


// Borrar tablas si existen
$respuesta = $conexion->query("SHOW TABLES");
while ($fila = $respuesta->fetch_array()) {
    $conexion->query("DROP TABLE IF EXISTS `$fila[0]`");
}

// Crear tablas y verificar
$tablas = [];

// Crear tablas
$tablas['usuarios'] = "
CREATE TABLE `usuarios` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `usuario` varchar(50) NOT NULL,
 `password` varchar(50) NOT NULL,
 `rol` enum('administrador','operario') NOT NULL,
 PRIMARY KEY (`id`)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
  ";


$tablas['tareas'] = "
 CREATE TABLE `tareas` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `nif_cif` varchar(20) NOT NULL,
 `persona_contacto` varchar(100) NOT NULL,
 `telefono` varchar(20) NOT NULL,
 `correo` varchar(100) DEFAULT NULL,
 `direccion` text NOT NULL,
 `poblacion` varchar(100) NOT NULL,
 `codigo_postal` char(5) NOT NULL,
 `provincia` char(2) NOT NULL,
 `descripcion` text DEFAULT NULL,
 `anotaciones_anteriores` text DEFAULT NULL,
 `estado` enum('B','P','R','C') NOT NULL DEFAULT 'P',
 `fecha_creacion` datetime DEFAULT current_timestamp(),
 `operario_encargado` varchar(50) DEFAULT NULL,
 `fecha_realizacion` date NOT NULL,
 `anotaciones_posteriores` text DEFAULT NULL,
 `fichero_resumen` varchar(255) DEFAULT NULL,
 PRIMARY KEY (`id`)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
  ";


$tablas['login_token'] = "
CREATE TABLE `login_token` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `usuario` varchar(100) NOT NULL,
 `selector_hash` varchar(255) NOT NULL,
 `validator_hash` varchar(255) NOT NULL,
 `expiry_date` datetime NOT NULL,
 `is_expired` tinyint(4) DEFAULT 0,
 PRIMARY KEY (`id`)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
";

$tablas['config_avanzada'] = "
CREATE TABLE `config_avanzada` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `provincia_defecto` varchar(2) DEFAULT NULL,
 `poblacion_defecto` varchar(50) DEFAULT NULL,
 `items_por_pagina` int(11) DEFAULT 5,
 `tiempo_sesion` int(11) NOT NULL,
 `tema` enum('claro','oscuro') DEFAULT 'claro',
 PRIMARY KEY (`id`)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
  ";

// Crear tablas y verificar errores
foreach ($tablas as $nombre => $sql) {
    if (!$conexion->query($sql)) {
        mostrarError("Error al crear la tabla <strong>$nombre</strong>: " . $conexion->error);
    }
}

// Crear trigger para tareas
$triggerSQL = "
CREATE TRIGGER `tr_no_modificar_fecha_creacion` 
BEFORE UPDATE ON `tareas`
FOR EACH ROW 
BEGIN
    SET NEW.fecha_creacion = OLD.fecha_creacion;
END;
";

if (!$conexion->query($triggerSQL)) {
    mostrarError("Error al crear el trigger de tareas: " . $conexion->error);
}

// Limpiar resultados pendientes del multi_query
while ($conexion->more_results() && $conexion->next_result()) {
}

// Insertar admin si se proporcionó
if ($adminUser && $adminPass) {
    if (!$conexion->query("INSERT INTO usuarios (usuario, password, rol) VALUES ('$adminUser','$adminPass','administrador')")) {
        mostrarError("Error al crear usuario administrador: " . $conexion->error);
    }
}

$conexion->query("INSERT INTO config_avanzada VALUES (1,'28','Madrid',5,30,'claro')");


$conexion->close();

echo "<!DOCTYPE html>
<html lang='es'>
<head>
<meta charset='UTF-8'>
<title>Instalación completada</title>
<style>
body { font-family: sans-serif; background:#eee; padding:40px; text-align:center; }
.box { background:#d1fae5; color:#065f46; padding:30px; max-width:500px; margin:auto; border-radius:8px; }
a { display:inline-block; margin-top:20px; padding:10px 20px; background:#2563eb; color:white; text-decoration:none; border-radius:6px; }
a:hover { background:#1e40af; }
</style>
</head>
<body>
<div class='box'>
<h2>Instalación completada correctamente</h2>
<p>El archivo <strong>config.php</strong> se ha creado con la configuración de conexión a la base de datos.</p>
<a href='../public'>Ir a la aplicación</a>
</div>
</body> 
</html>";
