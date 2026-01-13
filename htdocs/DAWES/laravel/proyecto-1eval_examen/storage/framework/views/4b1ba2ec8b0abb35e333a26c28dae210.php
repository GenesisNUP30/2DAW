<?php $__env->startSection('titulo', 'Login'); ?>

<?php $__env->startSection('estilos'); ?>
<style>
    .login-container {
        max-width: 450px;
        margin: 2rem auto;
        background: white;
        padding: 2.5rem;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .login-title {
        text-align: center;
        margin-bottom: 1.5rem;
        color: #2d3748;
        font-weight: 600;
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #2d3748;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        font-size: 1rem;
        border: 1px solid #cbd5e0;
        border-radius: 8px;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15);
        outline: none;
    }

    .btn-login {
        width: 100%;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border: none;
        padding: 0.75rem;
        font-size: 1.1rem;
        font-weight: 600;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-login:hover {
        background: linear-gradient(135deg, #764ba2, #667eea);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .alert-error {
        background: #fee;
        color: #c53030;
        padding: 0.75rem;
        border-radius: 8px;
        margin-bottom: 1.25rem;
        border: 1px solid #feb2b2;
        font-weight: 500;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('cuerpo'); ?>
<div class="login-container">
    <h1 class="login-title">
        <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
    </h1>

    <?php if(isset($error)): ?>
    <div class="alert-error">
        <i class="fas fa-exclamation-circle me-1"></i><?php echo e($error); ?>

    </div>
    <?php endif; ?>

    <form method="post">
        <div class="form-group">
            <label for="usuario" class="form-label">Usuario</label>
            <input type="text" id="usuario" name="usuario" class="form-control" autocomplete="username" required>
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" id="password" name="password" class="form-control" autocomplete="current-password" required>
        </div>

        <button type="submit" class="btn-login">
            <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
        </button>
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.plantilla01', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ushca\Desktop\Génesis\2DAW\htdocs\DAWES\laravel\proyecto-1eval\resources\views/login.blade.php ENDPATH**/ ?>