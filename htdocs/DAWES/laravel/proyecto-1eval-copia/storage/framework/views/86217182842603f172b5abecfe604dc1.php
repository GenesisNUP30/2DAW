<?php $__env->startSection('titulo', 'Lista de usuarios'); ?>

<?php $__env->startSection('estilos'); ?>
<style>
        .tabla-usuarios {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        font-size: 13px;
        background: #ffffff;
        border-radius: 12px;
        overflow: hidden;
    }

    .tabla-usuarios th,
    .tabla-usuarios td {
        padding: 10px;
        text-align: left;
        border-right: 1px solid #e2e8f0;
    }

    .tabla-usuarios th:last-child,
    .tabla-usuarios td:last-child {
        border-right: none;
    }

    .tabla-usuarios th {
        background: linear-gradient(145deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-weight: 500;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 0.8px;
    }

    .tabla-usuarios tr:nth-child(odd) {
        background-color: #f7fafc;
    }

    .tabla-usuarios tr:nth-child(even) {
        background-color: #edf2f7;
    }

    .tabla-usuarios tr:hover {
        background-color: #e6fffa;
        transform: scale(1.01);
        transition: all 0.3s ease;
    }

    .tabla-usuarios td {
        color: #4a5568;
        vertical-align: middle;
    }

    .tabla-usuarios td:last-child {
        text-align: center;
        min-width: 120px;
    }

    .tabla-usuarios button {
        margin: 2px;
        padding: 6px 10px;
        font-size: 11px;
        border: 2px solid transparent;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .tabla-usuarios button a {
        text-decoration: none;
        color: inherit;
        display: block;
    }

    .tabla-usuarios button:first-child {
        background: linear-gradient(135deg, #48bb78, #38a169);
        color: white;
    }

    .tabla-usuarios button:first-child:hover {
        background: linear-gradient(135deg, #38a169, #2f855a);
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(72, 187, 120, 0.3);
    }

    .tabla-usuarios button:last-child {
        background: linear-gradient(135deg, #f56565, #e53e3e);
        color: white;
    }

    .tabla-usuarios button:last-child:hover {
        background: linear-gradient(135deg, #e53e3e, #c53030);
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(245, 101, 101, 0.3);
    }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('cuerpo'); ?>
<div class="container">
    <h1>Lista de usuarios</h1>
    <table class="tabla-usuarios">
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $usuarios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $usuario): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($usuario['id']); ?></td>
                <td><?php echo e($usuario['usuario']); ?></td>
                <td><?php echo e($usuario['rol']); ?></td>
                <td>
                    <?php if($_SESSION['rol'] == 'administrador'): ?>
                    <button><a href="<?php echo miurl('editarusuario/' . $usuario['id']); ?>">Editar</a></button>
                    <button><a href="<?php echo miurl('eliminarusuario/' . $usuario['id']); ?>">Eliminar</a></button>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.plantilla01', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ushca\OneDrive\Escritorio\Génesis\2DAW\htdocs\DAWES\laravel\proyecto-1eval\resources\views/listarusuarios.blade.php ENDPATH**/ ?>