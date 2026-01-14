<?php $__env->startSection('titulo', 'Calificaciones de los alumnos'); ?>

<?php $__env->startSection('cuerpo'); ?>
    <h1>Calificaciones de los alumnos</h1>
    <table class="table table-striped table-border">
    <thead>
        <tr>
            <td style="text-align:center"><strong>Nombre</strong></td>
            <td colspan="2" style="text-align:center"><strong>Calificación</strong></td>        
        </tr>
    </thead>
    <tbody>
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
    </tbody>
    </table>
    
    <p>Número de alumnos en la clase <?php echo e(count($calificaciones)); ?> </p>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts/plantilla01', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\santiago\Desktop\xampp24-25\htdocs\ut5\EjemplosBladeJuev\resources\views/10calificaciones.blade.php ENDPATH**/ ?>