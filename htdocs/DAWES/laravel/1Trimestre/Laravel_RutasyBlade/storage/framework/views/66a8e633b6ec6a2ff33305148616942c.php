<!DOCTYPE html>
<html>
    <head>
        <title>Calificaciones</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <h1>Calificaciones de los alumnos</h1>
        <table>
        <?php $__currentLoopData = $alumnos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alumno): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><?php echo e($alumno['nombre']); ?></td>
            <td><?php echo e($alumno['calificacion']); ?></td>
            <td>
            <?php if(<?php echo e($alumno['calificacion']<5); ?>): ?>
                Susp.
            <?php else: ?>
                Aprob.
            <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        
        <p>Número de alumnos en la clase <?php echo e(count($alumnos)); ?> 
    </body>
</html>

<?php /**PATH C:\Users\santiago\Desktop\xampp24-25\htdocs\ut5\EjemplosBladeJuev\resources\views/04calificaciones.blade.php ENDPATH**/ ?>