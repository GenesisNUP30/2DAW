<?php $__env->startSection('titulo', 'Hola con plantilla'); ?>

<?php $__env->startSection('cuerpo'); ?>
<div class="col-12">
    <h1>Saludo utilizando plantilla</h1>
</div>
<div class="col-12">
    <h2>¡Hola mundo!!!!</h2>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts/plantilla01', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\ApacheDocs\Blade\views/11holaconplantilla.blade.php ENDPATH**/ ?>