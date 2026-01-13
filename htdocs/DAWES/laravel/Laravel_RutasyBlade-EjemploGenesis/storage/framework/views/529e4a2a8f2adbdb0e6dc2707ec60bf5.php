<?php $__env->startSection('titulo', 'Hola con plantilla'); ?>

<?php $__env->startSection('estilos'); ?>
<style>
        .error {
            color: red;
        }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('cuerpo'); ?>
   <h1>Alta de Tarea</h1>

   <?php echo $__env->make('form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts/plantilla01', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ushca\Desktop\Génesis\2DAW\htdocs\DAWES\laravel\Laravel_RutasyBlade-EjemploGenesis\resources\views/alta.blade.php ENDPATH**/ ?>