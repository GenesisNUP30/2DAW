<!DOCTYPE html>
<html>
    <head>
        <title>Blade 1</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <h1>Saludos</h1>
        <ul>
        <?php $__currentLoopData = $nombres; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nombre): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <li>Hola <?php echo e($nombre); ?></li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
        <?php if(count($nombres)>5): ?>
            <p>Hay muchos nombres</p>
        <?php endif; ?>
    </body>
</html>

<?php /**PATH C:\Users\santi\Documents\xampp-7.3.9\htdocs\Blade\views/03saludos.blade.php ENDPATH**/ ?>