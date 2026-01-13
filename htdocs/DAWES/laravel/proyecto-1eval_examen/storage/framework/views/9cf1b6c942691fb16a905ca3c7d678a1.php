

<?php $__env->startSection('titulo', 'Eliminar Tarea'); ?>

<?php $__env->startSection('estilos'); ?>
<style>
    .alert-warning {
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        color: #856404;
        padding: 1.25rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }

    .tarea-datos {
        background: #f7f7f7;
        border: 1px solid #cbd5e0;
        border-radius: 6px;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }

    .tarea-datos p {
        margin: 0.25rem 0;
    }

    .btn-confirm {
        background: linear-gradient(135deg, #e53e3e, #c53030);
        color: white;
        border: none;
        padding: 0.6rem 1.5rem;
        font-size: 1rem;
        font-weight: 600;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-confirm:hover {
        background: linear-gradient(135deg, #c53030, #9b2c2c);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(229, 62, 62, 0.3);
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

    h1 {
        margin-bottom: 1.5rem;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('cuerpo'); ?>
<h1>
    <i class="fas fa-trash-alt me-2"></i>Eliminar Tarea
</h1>

<div class="alert-warning">
    <i class="fas fa-exclamation-triangle me-2"></i>
    <strong>¿Estás seguro de que deseas eliminar la tarea de <span class="text-danger"><?php echo e($tarea['persona_contacto']); ?></span>?</strong>
    <br>
    Esta acción no se puede deshacer.
</div>

<div class="tarea-datos">
    <p><strong>NIF/CIF:</strong> <?php echo e($tarea['nif_cif']); ?></p>
    <p><strong>Persona de contacto:</strong> <?php echo e($tarea['persona_contacto']); ?></p>
    <p><strong>Teléfono:</strong> <?php echo e($tarea['telefono']); ?></p>
    <p><strong>Correo:</strong> <?php echo e($tarea['correo']); ?></p>
    <p><strong>Descripción:</strong> <?php echo e($tarea['descripcion']); ?></p>
    <p><strong>Fecha de realización:</strong> <?php echo e(\App\Models\Funciones::cambiarFormatoFecha($tarea['fecha_realizacion'])); ?></p>
    <p><strong>Operario encargado:</strong> <?php echo e($tarea['operario_encargado']); ?></p>
    <p><strong>Estado:</strong> <?php echo e($tarea['estado']); ?></p>
</div>

<form action="<?php echo e(url('eliminar/' . $tarea['id'])); ?>" method="POST">
    <?php echo csrf_field(); ?>
    <button type="submit" class="btn-confirm">
        <i class="fas fa-check-circle me-1"></i>Sí, eliminar
    </button>
    <a href="<?php echo e(url('/')); ?>" class="btn-cancel">
        <i class="fas fa-times me-1"></i>Cancelar
    </a>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.plantilla01', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ushca\Desktop\Génesis\2DAW\htdocs\DAWES\laravel\proyecto-1eval\resources\views/eliminar.blade.php ENDPATH**/ ?>