<?php $__env->startSection('titulo', 'Modificar Tarea'); ?>

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

    .form-control,
    .form-select {
        border: 1px solid #cbd5e0;
        border-radius: 6px;
        padding: 0.5rem 0.75rem;
        font-size: 1rem;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-control:focus,
    .form-select:focus {
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
    }

    .btn-cancel:hover {
        background: linear-gradient(135deg, #4a5568, #2d3748);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(71, 80, 96, 0.3);
    }

    .form-row {
        margin-bottom: 1.25rem;
    }

    textarea.form-control {
        min-height: 100px;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('cuerpo'); ?>
<h1 class="mb-4">
    <i class="fas fa-edit me-2"></i>Modificar Tarea
</h1>

<form action="<?php echo e(url('modificar/' . $id)); ?>" method="POST" enctype="multipart/form-data">
    <div class="form-row">
        <label class="form-label">NIF/CIF:</label>
        <input type="text" name="nif_cif" class="form-control" value="<?php echo e($nif_cif ?? ''); ?>">
        <?php echo \App\Models\Funciones::verErrores('nif_cif'); ?>

    </div>

    <div class="form-row">
        <label class="form-label">Persona de contacto:</label>
        <input type="text" name="persona_contacto" class="form-control" value="<?php echo e($persona_contacto ?? ''); ?>">
        <?php echo \App\Models\Funciones::verErrores('persona_contacto'); ?>

    </div>

    <div class="form-row">
        <label class="form-label">Teléfono:</label>
        <input type="text" name="telefono" class="form-control" value="<?php echo e($telefono ?? ''); ?>">
        <?php echo \App\Models\Funciones::verErrores('telefono'); ?>

    </div>

    <div class="form-row">
        <label class="form-label">Correo electrónico:</label>
        <input type="text" name="correo" class="form-control" value="<?php echo e($correo ?? ''); ?>">
        <?php echo \App\Models\Funciones::verErrores('correo'); ?>

    </div>

    <div class="form-row">
        <label class="form-label">Descripción de la tarea:</label>
        <textarea name="descripcion" class="form-control"><?php echo e($descripcion ?? ''); ?></textarea>
        <?php echo \App\Models\Funciones::verErrores('descripcion'); ?>

    </div>

    <div class="form-row">
        <label class="form-label">Dirección:</label>
        <input type="text" name="direccion" class="form-control" value="<?php echo e($direccion); ?>">
    </div>

    <div class="form-row">
        <label class="form-label">Población:</label>
        <input type="text" name="poblacion" class="form-control" value="<?php echo e($poblacion); ?>">
    </div>

    <div class="form-row">
        <label class="form-label">Código Postal:</label>
        <input type="text" name="codigo_postal" class="form-control" value="<?php echo e($codigo_postal ?? ''); ?>">
    </div>

    <div class="form-row">
        <label class="form-label">Provincia:</label>
        <select name="provincia" class="form-select">
            <option value="">Seleccione provincia</option>
            <?php echo \App\Models\Funciones::mostrarProvincias($provincia ?? ''); ?>

        </select>
        <?php echo \App\Models\Funciones::verErrores('provincia'); ?>

    </div>

    <div class="form-row">
        <label class="form-label">Estado:</label>
        <select name="estado" class="form-select">
            <option value="B" <?php echo e(($estado ?? '') == "B" ? "selected" : ""); ?>>Esperando ser aprobada</option>
            <option value="P" <?php echo e(($estado ?? '') == "P" ? "selected" : ""); ?>>Pendiente</option>
            <option value="R" <?php echo e(($estado ?? '') == "R" ? "selected" : ""); ?>>Realizada</option>
            <option value="C" <?php echo e(($estado ?? '') == "C" ? "selected" : ""); ?>>Cancelada</option>
        </select>
    </div>

    <div class="form-row">
        <label class="form-label">Operario encargado:</label>
        <select name="operario_encargado" class="form-select">
            <option value="">Seleccione operario</option>
            <option value="Juan Pérez" <?php echo e(($operario_encargado ?? '') == "Juan Pérez" ? "selected" : ""); ?>>Juan Pérez</option>
            <option value="María López" <?php echo e(($operario_encargado ?? '') == "María López" ? "selected" : ""); ?>>María López</option>
            <option value="Carlos Ruiz" <?php echo e(($operario_encargado ?? '') == "Carlos Ruiz" ? "selected" : ""); ?>>Carlos Ruiz</option>
            <option value="Ana María Fernández" <?php echo e(($operario_encargado ?? '') == "Ana María Fernández" ? "selected" : ""); ?>>Ana María Fernández</option>
            <option value="Sara Martínez" <?php echo e(($operario_encargado ?? '') == "Sara Martínez" ? "selected" : ""); ?>>Sara Martínez</option>
            <option value="Lucía Hurtado" <?php echo e(($operario_encargado ?? '') == "Lucía Hurtado" ? "selected" : ""); ?>>Lucía Hurtado</option>
        </select>
    </div>

    <div class="form-row">
        <label class="form-label">Fecha de realización:</label>
        <input type="date" name="fecha_realizacion" class="form-control" value="<?php echo e($fecha_realizacion ?? ''); ?>">
        <?php echo \App\Models\Funciones::verErrores('fecha_realizacion'); ?>

    </div>

    <div class="form-row">
        <label class="form-label">Anotaciones anteriores:</label>
        <textarea id="anotaciones_anteriores" name="anotaciones_anteriores" class="form-control"><?php echo e($anotaciones_anteriores ?? ''); ?></textarea>
    </div>

    <div class="form-row">
        <label class="form-label">Anotaciones posteriores:</label>
        <textarea id="anotaciones_posteriores" name="anotaciones_posteriores" class="form-control"><?php echo e($anotaciones_posteriores ?? ''); ?></textarea>
    </div>

    <div class="form-row">
        <label class="form-label">Fichero resumen:</label>
        <input type="file" id="fichero_resumen" name="fichero_resumen" class="form-control">
    </div>

    <div class="form-row">
        <label class="form-label">Fotos del trabajo:</label>
        <input type="file" id="fotos" name="fotos[]" multiple class="form-control">
    </div>

    <div class="mt-4">
        <a href="<?php echo e(url('/')); ?>" class="btn-cancel">
            <i class="fas fa-times me-1"></i>Cancelar
        </a>
        <button type="submit" class="btn-submit">
            <i class="fas fa-sync-alt me-1"></i>Actualizar tarea
        </button>
    </div>
</form>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.plantilla01', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ushca\Desktop\Génesis\2DAW\htdocs\DAWES\laravel\proyecto-1eval\resources\views/modificar.blade.php ENDPATH**/ ?>