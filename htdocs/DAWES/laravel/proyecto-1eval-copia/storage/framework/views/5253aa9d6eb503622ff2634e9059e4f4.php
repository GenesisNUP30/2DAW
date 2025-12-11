<?php $__env->startSection('titulo', 'Editar usuario'); ?>

<?php $__env->startSection('cuerpo'); ?>

<h2>Editar usuario</h2>

<form method="POST" action="<?php echo e(miurl('editarusuario/' . $id)); ?>">
    <div class="form-group">
        <label>Nombre de usuario</label>
        <input type="text" name="usuario_nuevo" class="form-control" value="<?php echo e($usuario_nuevo ?? ''); ?>">

        <?php echo \App\Models\Funciones::verErrores('usuario_nuevo'); ?>

    </div>

    <div class="form-group">
        <label>Contraseña antigua</label>
        <input type="password" name="password_antigua" class="form-control">
        <?php echo \App\Models\Funciones::verErrores('password_antigua'); ?>

    </div>

    <div class="form-group">
        <label>Nueva contraseña</label>
        <input type="password" name="password_nueva" class="form-control">
        <?php echo \App\Models\Funciones::verErrores('password_nueva'); ?>

    </div>

    <div class="form-group">
        <label>Repetir nueva contraseña</label>
        <input type="password" name="password_nueva2" class="form-control">
        <?php echo \App\Models\Funciones::verErrores('password_nueva2'); ?>

    </div>

    <?php if($rol_logueado == 'administrador'): ?>
    <div class="form-group">
        <label>Rol</label>
        <select name="rol_nuevo" class="form-control">
            <option value="">Seleccione un rol</option>
            <option value="administrador" <?php echo e($rol_nuevo=='administrador'?'selected':''); ?>>Administrador</option>
            <option value="operario" <?php echo e($rol_nuevo=='operario'?'selected':''); ?>>Operario</option>
        </select>
        <?php echo \App\Models\Funciones::verErrores('rol_nuevo'); ?>

    </div>
    <?php endif; ?>

    <button type="submit" class="btn btn-primary">Actualizar</button>
    <a href="<?php echo e(miurl('/')); ?>" class="btn btn-secondary">Cancelar</a>
    
</form>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.plantilla01', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ushca\OneDrive\Escritorio\Génesis\2DAW\htdocs\DAWES\laravel\proyecto-1eval\resources\views/editarusuario.blade.php ENDPATH**/ ?>