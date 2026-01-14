<?php $__env->startSection('titulo', 'Pagina principal'); ?>
<?php $__env->startSection('estilos'); ?>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4edf9 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #2d3748;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            padding: 40px;
            width: 100%;
            max-width: 600px;
            text-align: center;
        }

        h1 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 32px;
            color: #1a202c;
        }

        .lista-opciones {
            list-style: none;
        }

        .lista-opciones li {
            margin-bottom: 2px;
        }

        .lista-opciones a {
            display: block;
            padding: 14px 20px;
            background-color: #ffffff;
            color: #3182ce;
            text-decoration: none;
            border: 2px solid #cbd5e0;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 500;
            transition: all 0.25s ease;
        }

        .lista-opciones a:hover {
            background-color: #ebf4ff;
            border-color: #3182ce;
            color: #2c5282;
        }

    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('cuerpo'); ?>
    <div class="container">
        <h1>Gestión de Tareas</h1>
        <ul class="lista-opciones">
            <li><a href="<?php echo url('alta'); ?>">Alta de Tarea</a></li>
            <li><a href="modificarTarea_ficheros/modificar_tarea.php">Modificar Tarea</a></li>
            <li><a href="eliminarTarea_ficheros/eliminar_tarea_datos.php">Eliminar Tarea</a></li>
        </ul>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts/plantilla01', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ushca\OneDrive\Escritorio\Génesis\2DAW\htdocs\DAWES\laravel\Laravel_RutasyBlade-EjemploGenesis\resources\views/index.blade.php ENDPATH**/ ?>