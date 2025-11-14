<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Confirmar Eliminación</title>
</head>
<body>
    <div>
        <h2>⚠️ Confirmar eliminación</h2>
        
        <div>
            <p><strong>ID Tarea:</strong> <?= htmlspecialchars($idTarea) ?></p>
            <p><strong>Cliente:</strong> <?= htmlspecialchars($personaNombre) ?> (<?= htmlspecialchars($nifCif) ?>)</p>
            <p><strong>Descripción:</strong> <?= htmlspecialchars($descripcionTarea) ?></p>
            <p><strong>Fecha realización:</strong> <?= htmlspecialchars($fechaRealizacion) ?></p>
            <p><strong>Estado:</strong> 
                <?php
                $estados = ['B' => 'Esperando aprobación', 'P' => 'Pendiente', 'R' => 'Realizada', 'C' => 'Cancelada'];
                echo htmlspecialchars(isset($estados[$estadoTarea]) ? $estados[$estadoTarea] : $estadoTarea);
                ?>
            </p>
        </div>

        <p>¿Está seguro de que desea <strong>eliminar permanentemente</strong> esta tarea?</p>

        <form method="POST" action="eliminar_tarea_confirmar.php">
            <input type="hidden" name="id_tarea" value="<?= htmlspecialchars($idTarea) ?>">
            <div>
                <button type="submit" name="confirmar" value="si">Sí, eliminar</button>
                <br><br>
                <a href="../index.php">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>