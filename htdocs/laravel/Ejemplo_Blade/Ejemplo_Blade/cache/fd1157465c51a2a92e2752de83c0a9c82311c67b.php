<!DOCTYPE html>
<html>
    <head>
        <title>Calificaciones</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <h1>Calificaciones de los alumnos</h1>
        <table border="1">
        <tr>
            <td>Nombre</td>
            <td colspan="2">Calificación</td>
            
        </tr>

        <?php $__currentLoopData = $calificaciones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $calificacion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><?php echo e($calificacion['nombre']); ?></td>
            <td><?php echo e($calificacion['calificacion']); ?></td>
            <td>
            <?php if( $calificacion['calificacion'] < 5 ): ?>
                Susp.
            <?php else: ?>
                Aprob.
            <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </table>
        
        <p>Número de alumnos en la clase <?php echo e(count($calificaciones)); ?> 
    </body>
</html>

<?php /**PATH C:\ApacheDocs\Blade\views/04calificaciones1.blade.php ENDPATH**/ ?>