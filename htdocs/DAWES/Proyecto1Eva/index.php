<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Tareas - Menú Principal</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #f0f4f8 0%, #e2e8f0 100%);
            font-family: 'Arial', sans-serif;
            color: #2d3748;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            padding: 30px;
            width: fit-content;
            max-width: 95vw;
            text-align: center;
            overflow-x: auto;
        }

        h1 {
            font-size: 26px;
            font-weight: 600;
            margin-bottom: 28px;
            color: #1a202c;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .tabla-tareas {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 13px;
            background: #ffffff;
            border: 2px solid #cbd5e0;
            border-radius: 12px;
            overflow: hidden;
        }

        .tabla-tareas th,
        .tabla-tareas td {
            padding: 10px;
            text-align: left;
            border-right: 1px solid #e2e8f0;
        }

        .tabla-tareas th:last-child,
        .tabla-tareas td:last-child {
            border-right: none;
        }

        .tabla-tareas th {
            background: linear-gradient(145deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.8px;
        }

        .tabla-tareas tr:nth-child(odd) {
            background-color: #f7fafc;
        }

        .tabla-tareas tr:nth-child(even) {
            background-color: #edf2f7;
        }

        .tabla-tareas tr:hover {
            background-color: #e6fffa;
            transform: scale(1.01);
            transition: all 0.3s ease;
        }

        .tabla-tareas td {
            color: #4a5568;
            vertical-align: middle;
        }

        .tabla-tareas td:last-child {
            text-align: center;
            min-width: 120px;
        }

        /* Botones dentro de la tabla */
        .tabla-tareas button {
            margin: 2px;
            padding: 6px 10px;
            font-size: 11px;
            border: 2px solid transparent;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .tabla-tareas button a {
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .tabla-tareas button:first-child {
            background: linear-gradient(135deg, #48bb78, #38a169);
            color: white;
        }

        .tabla-tareas button:first-child:hover {
            background: linear-gradient(135deg, #38a169, #2f855a);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(72, 187, 120, 0.3);
        }

        .tabla-tareas button:last-child {
            background: linear-gradient(135deg, #f56565, #e53e3e);
            color: white;
        }

        .tabla-tareas button:last-child:hover {
            background: linear-gradient(135deg, #e53e3e, #c53030);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(245, 101, 101, 0.3);
        }
    </style>

</head>

<body>
    <div class="container">
        <h1>Gestión de Tareas</h1>
        <table class="tabla-tareas">
            <thead>
                <th>ID</th>
                <!-- <th>NIF/CIF</th> -->
                <th>Persona de contacto</th>
                <th>Teléfono</th>
                <th>Correo</th>
                <th>Descripción</th>
                <!-- <th>Dirección</th>
                <th>Población</th>
                <th>Código Postal</th>
                <th>Provincia</th> -->
                <th>Estado</th>
                <th>Operario</th>
                <th>Fecha Realización</th>
                <!-- <th>Anotaciones Anteriores</th>
                <th>Anotaciones Posteriores</th>
                <th>Fichero Resumen</th>
                <th>Fotos</th> -->
                <th>Acciones</th>
            </thead>
            <tbody>
                <?php
                include 'funciones.php';
                mostrarTablaTareas($resultado);
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>