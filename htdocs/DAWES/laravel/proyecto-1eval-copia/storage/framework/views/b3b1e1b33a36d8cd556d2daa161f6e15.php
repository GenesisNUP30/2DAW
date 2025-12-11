<?php $__env->startSection('titulo', 'Completar Tarea'); ?>

<?php $__env->startSection('estilos'); ?>
<style>
    .error {
        color: red;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('cuerpo'); ?>
<h1>Completar Tarea</h1>

<form action="<?php echo e(url('completar/' . $id)); ?>" method="POST" enctype="multipart/form-data">
    <label>NIF/CIF:</label><br>
    <input type="text" name="nif_cif" value="<?php echo e($nif_cif); ?>" readonly><br>
    <br>

    <label>Persona de contacto:</label><br>
    <input type="text" name="persona_contacto" value="<?php echo e($persona_contacto); ?>" readonly><br>
    <br>

    <label>Descripción de la tarea:</label><br>
    <textarea name="descripcion" readonly><?php echo e($descripcion); ?></textarea><br>
    <br>

    <label>Estado:</label><br>
    <input type="radio" name="estado" value="R" checked> Completada<br>
    <input type="radio" name="estado" value="C" <?php echo e($estado=="C" ? "checked" : ""); ?>> Cancelada<br>
    <br>
    <?php echo \App\Models\Funciones::verErrores('estado'); ?>


    <label>Fecha de realización:</label><br>
    <input type="date" name="fecha_realizacion" value="<?php echo e($fecha_realizacion); ?>" readonly><br>
    <br>

    <label for="anotaciones_anteriores">Anotaciones anteriores:</label><br>
    <textarea id="anotaciones_anteriores" name="anotaciones_anteriores" readonly><?php echo e($anotaciones_anteriores); ?></textarea><br><br>

    <label for="anotaciones_posteriores">Anotaciones posteriores:</label><br>
    <textarea id="anotaciones_posteriores" name="anotaciones_posteriores"><?php echo e($anotaciones_posteriores); ?></textarea><br><br>
    <?php echo \App\Models\Funciones::verErrores('anotaciones_posteriores'); ?>

    
    <label for="fichero_resumen">Fichero resumen:</label>
    <input type="file" id="fichero_resumen" name="fichero_resumen" multiple><br><br>

    <a class="btn btn-cancel" href="<?php echo e(url('/')); ?>">Cancelar</a>
    <input type="submit" value="Completar tarea">
</form>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.plantilla01', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ushca\OneDrive\Escritorio\Génesis\2DAW\htdocs\DAWES\laravel\proyecto-1eval\resources\views/completar.blade.php ENDPATH**/ ?>