<?php $__env->startSection('titulo', 'Editar Usuario'); ?>

<?php $__env->startSection('estilos'); ?>
<style>
    .error {
        color: #e53e3e;
        font-size: 0.875rem;
        margin-top: 0.25rem;
        display: block;
    }

    .form-label {
        font-weight: 600;
        margin-top: 1.25rem;
        color: #2d3748;
        display: block;
    }

    .form-control, .form-select {
        border: 1px solid #cbd5e0;
        border-radius: 6px;
        padding: 0.5rem 0.75rem;
        font-size: 1rem;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15);
        outline: none;
    }

    .btn-submit {
        background: linear-gradient(135deg, #3182ce, #2b6cb0);
        color: white;
        border: none;
        padding: 0.6rem 1.5rem;
        font-size: 1rem;
        font-weight: 600;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-submit:hover {
        background: linear-gradient(135deg, #2b6cb0, #2c5282);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(49, 130, 206, 0.3);
    }

    .btn-cancel {
        background: linear-gradient(135deg, #718096, #4a5568);
        color: white;
        text-decoration: none;
        padding: 0.6rem 1.5rem;
        font-size: 1rem;
        font-weight: 600;
        border-radius: 6px;
        display: inline-block;
        transition: all 0.2s ease;
        margin-left: 0.75rem;
    }

    .btn-cancel:hover {
        background: linear-gradient(135deg, #4a5568, #2d3748);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(71, 80, 96, 0.3);
    }

    .form-row {
        margin-bottom: 1.25rem;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('cuerpo'); ?>
<h1 class="mb-4">
    <i class="fas fa-user-edit me-2"></i>Editar Usuario
</h1>

<form method="POST" action="<?php echo e(miurl('editarusuario/' . $id)); ?>">
    <div class="form-row">
        <label class="form-label">Nombre de usuario</label>
        <input type="text" name="usuario_nuevo" class="form-control" value="<?php echo e($usuario_nuevo ?? ''); ?>">
        <?php echo \App\Models\Funciones::verErrores('usuario_nuevo'); ?>

    </div>

    <div class="form-row">
        <label class="form-label">Contraseña actual <small class="text-muted">(solo si desea cambiarla)</small></label>
        <input type="password" name="password_antigua" class="form-control">
        <?php echo \App\Models\Funciones::verErrores('password_antigua'); ?>

    </div>

    <div class="form-row">
        <label class="form-label">Nueva contraseña</label>
        <input type="password" name="password_nueva" class="form-control">
        <?php echo \App\Models\Funciones::verErrores('password_nueva'); ?>

    </div>

    <div class="form-row">
        <label class="form-label">Confirmar nueva contraseña</label>
        <input type="password" name="password_nueva2" class="form-control">
        <?php echo \App\Models\Funciones::verErrores('password_nueva2'); ?>

    </div>

    <?php if($rol_logueado == 'administrador'): ?>
    <div class="form-row">
        <label class="form-label">Rol</label>
        <select name="rol_nuevo" class="form-select">
            <option value="">Seleccione un rol</option>
            <option value="administrador" <?php if($rol_nuevo=='administrador'): ?> selected <?php endif; ?>>Administrador</option>
            <option value="operario" <?php if($rol_nuevo=='operario'): ?> selected <?php endif; ?>>Operario</option>
        </select>
        <?php echo \App\Models\Funciones::verErrores('rol_nuevo'); ?>

    </div>
    <?php endif; ?>

    <div class="mt-4">
        <button type="submit" class="btn-submit">
            <i class="fas fa-sync-alt me-1"></i>Actualizar
        </button>
        <a href="<?php echo e(miurl('/')); ?>" class="btn-cancel">
            <i class="fas fa-times me-1"></i>Cancelar
        </a>
    </div>
</form>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.plantilla01', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ushca\Desktop\Génesis\2DAW\htdocs\DAWES\laravel\proyecto-1eval\resources\views/editarusuario.blade.php ENDPATH**/ ?>