<?php
$error = $_GET['error'] ?? '';
$mensaje = '';
if ($error === 'config') {
    $mensaje = "⚠ La aplicación ya parece estar instalada. El archivo <strong>app/config.php</strong> existe.";
} elseif ($error === 'conexion') {
    $mensaje = "❌ Error de conexión a la base de datos. Comprueba tus credenciales.";
} elseif ($error === 'crear_config') {
    $mensaje = "❌ No se pudo crear el archivo <strong>app/config.php</strong>. Revisa permisos.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalador</title>
    <style>
        body {
            font-family: sans-serif;
            background: #eee;
            padding: 40px;
        }

        .box {
            background: white;
            padding: 30px;
            max-width: 400px;
            margin: auto;
            border-radius: 8px;
        }

        input {
            width: 100%;
            padding: 8px;
            margin: 6px 0;
        }

        button {
            padding: 10px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 6px;
        }
    </style>
</head>

<body>
    <div class="box">
        <h2>Instalar Gestor de tareas/incidencias</h2>

        <div class="alert">
            La instalación borrará todas las tablas existentes en la base de datos.
        </div>

        <?php if ($mensaje): ?>
            <div class="alert" style="background:#fee2e2;color:#b91c1c;"><?php echo $mensaje; ?></div>
        <?php endif; ?>
        <p><strong>Atención:</strong> La instalación borrará todas las tablas existentes en la base de datos seleccionada.</p>

        <form method="post" action="install.php">
            Servidor BD:
            <input type="text" name="host" value="localhost">

            Base de datos:
            <input type="text" name="bd">

            Usuario:
            <input type="text" name="user">

            Contraseña:
            <input type="password" name="pass">

            <hr>
            <h3>Configuración avanzada</h3>
            <p>Si lo desea, puede introducir el nombre y contraseña del usuario administrador de la aplicación.</p>
            <p>En caso contrario, tenga en cuenta que no se creará ningún usuario por defecto y tendrá que añadirlo manualmente en la base de datos más tarde.</p>

            <label>Usuario administrador:</label>
            <input type="text" name="admin_user">

            <label>Contraseña administrador:</label>
            <input type="password" name="admin_pass">

            <button>Instalar</button>

        </form>
    </div>
</body>

</html>