<?php $__env->startSection('titulo', 'Hola con plantilla'); ?>

<?php $__env->startSection('cuerpo'); ?>
<div class="col-12">
    <h1>Saludo utilizando plantilla</h1>
</div>
<div class="col-12">
    <h2>¡Hola mundo!!!!</h2>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts/plantilla01', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\santiago\Desktop\xampp24-25\htdocs\ut5\EjemplosBladeJuev\resources\views/11holaconplantilla.blade.php ENDPATH**/ ?>