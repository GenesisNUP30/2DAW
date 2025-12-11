<?php $__env->startSection('titulo', 'Modificar Tarea'); ?>

<?php $__env->startSection('estilos'); ?>
<style>
    .error {
        color: red;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('cuerpo'); ?>
   <h1>Modificar Tarea</h1>

   <form action="<?php echo e(url('modificar/' . $id)); ?>" method="POST" enctype="multipart/form-data">
        <label>NIF/CIF:</label><br>
        <input type="text" name="nif_cif" value="<?php echo e($nif_cif); ?>"><br>
        <?php echo \App\Models\Funciones::verErrores('nif_cif'); ?>

        <br>

        <label>Persona de contacto:</label><br>
        <input type="text" name="persona_contacto" value="<?php echo e($persona_contacto); ?>"><br>
        <?php echo \App\Models\Funciones::verErrores('persona_contacto'); ?>

        <br>

        <label>Teléfono:</label><br>
        <input type="text" name="telefono" value="<?php echo e($telefono); ?>"><br>
        <?php echo \App\Models\Funciones::verErrores('telefono'); ?>

        <br>

        <label>Correo electrónico:</label><br>
        <input type="text" name="correo" value="<?php echo e($correo); ?>"><br>
        <?php echo \App\Models\Funciones::verErrores('correo'); ?>

        <br>

        <label>Descripción de la tarea:</label><br>
        <textarea name="descripcion"><?php echo e($descripcion); ?></textarea><br>
        <?php echo \App\Models\Funciones::verErrores('descripcion'); ?>

        <br>

        <label>Dirección:</label><br>
        <input type="text" name="direccion" value="<?php echo e($direccion); ?>"><br><br>

        <label>Población:</label><br>
        <input type="text" name="poblacion" value="<?php echo e($poblacion); ?>"><br><br>

        <label>Código Postal:</label><br>
        <input type="text" name="codigo_postal" value="<?php echo e($codigo_postal); ?>"><br><br>

        <label>Provincia:</label><br>
        <select name="provincia">
            <option value="">Seleccione provincia</option>
            <?php echo \App\Models\Funciones::mostrarProvincias($provincia); ?>

        </select><br>
        <?php echo \App\Models\Funciones::verErrores('provincia'); ?>

        <br>

        <label>Estado:</label><br>
        <select name="estado">
            <option value="B" <?php echo e($estado == "B" ? "selected" : ""); ?>>Esperando ser aprobada</option>
            <option value="P" <?php echo e($estado == "P" ? "selected" : ""); ?>>Pendiente</option>
            <option value="R" <?php echo e($estado == "R" ? "selected" : ""); ?>>Realizada</option>
            <option value="C" <?php echo e($estado == "C" ? "selected" : ""); ?>>Cancelada</option>
        </select><br><br>

        <label>Operario encargado:</label><br>
        <select name="operario_encargado">
            <option value="">Seleccione operario</option>
            <option value="Juan Pérez" <?php echo e($operario_encargado == "Juan Pérez" ? "selected" : ""); ?>>Juan Pérez</option>
            <option value="María López" <?php echo e($operario_encargado == "María López" ? "selected" : ""); ?>>María López</option>
            <option value="Carlos Ruiz" <?php echo e($operario_encargado == "Carlos Ruiz" ? "selected" : ""); ?>>Carlos Ruiz</option>
            <option value="Ana María Fernández" <?php echo e($operario_encargado == "Ana María Fernández" ? "selected" : ""); ?>>Ana María Fernández</option>
            <option value="Sara Martínez" <?php echo e($operario_encargado == "Sara Martínez" ? "selected" : ""); ?>>Sara Martínez</option>
            <option value="Lucía Hurtado" <?php echo e($operario_encargado == "Lucía Hurtado" ? "selected" : ""); ?>>Lucía Hurtado</option>
        </select><br><br>

        <label>Fecha de realización:</label><br>
        <input type="date" name="fecha_realizacion" value="<?php echo e($fecha_realizacion); ?>"><br>
        <?php echo \App\Models\Funciones::verErrores('fecha_realizacion'); ?>

        <br>

        <label for="anotaciones_anteriores">Anotaciones anteriores:</label><br>
        <textarea id="anotaciones_anteriores" name="anotaciones_anteriores"><?php echo e($anotaciones_anteriores); ?></textarea><br><br>

        <label for="anotaciones_posteriores">Anotaciones posteriores:</label><br>
        <textarea id="anotaciones_posteriores" name="anotaciones_posteriores"><?php echo e($anotaciones_posteriores); ?></textarea><br><br>
        
        <label for="fichero_resumen">Fichero resumen:</label>
        <input type="file" id="fichero_resumen" name="fichero_resumen"><br><br>

        <label for="fotos">Fotos del trabajo:</label>
        <input type="file" id="fotos" name="fotos[]" multiple><br><br>

        <a class="btn btn-cancel" href="<?php echo e(url('/')); ?>">Cancelar</a>
        <input type="submit" value="Actualizar tarea">
    </form>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.plantilla01', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ushca\OneDrive\Escritorio\Génesis\2DAW\htdocs\DAWES\laravel\proyecto-1eval\resources\views/modificar.blade.php ENDPATH**/ ?>