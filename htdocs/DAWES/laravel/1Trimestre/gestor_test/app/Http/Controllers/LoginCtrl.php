<?php

namespace App\Http\Controllers;

use App\Models\Sesion;

/**
 * @class LoginCtrl
 *
 * @brief Controlador encargado de la autenticación de usuarios.
 *
 * Este controlador gestiona todo el ciclo de autenticación del sistema:
 * - Visualización del formulario de inicio de sesión.
 * - Validación de credenciales de acceso.
 * - Redirección tras login correcto o incorrecto.
 * - Cierre de sesión del usuario.
 *
 * Se apoya en el modelo {@see Sesion} para realizar las comprobaciones
 * de autenticación y mantener el estado de sesión del usuario.
 *
 * @package App\Http\Controllers
 */
class LoginCtrl
{
    /**
     * @brief Muestra el formulario de login o procesa el envío de credenciales.
     *
     * Este método realiza las siguientes acciones:
     * - Comprueba si el usuario ya está autenticado y, en tal caso,
     *   lo redirige directamente a la página principal.
     * - Si se reciben datos mediante una petición POST, valida
     *   las credenciales introducidas (usuario y contraseña).
     * - Si las credenciales son correctas, redirige al inicio.
     * - Si las credenciales son incorrectas, vuelve a mostrar el
     *   formulario con un mensaje de error.
     *
     * Cuando no se reciben datos POST, se muestra únicamente el
     * formulario de inicio de sesión.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     * Devuelve la vista `login` o una redirección a la página principal.
     */
    public function login()
    {
        $model = Sesion::getInstance();

        // Si el usuario ya está autenticado, se redirige al inicio
        if ($model->isLogged()) {
            miredirect('/');
        }

        // Procesamiento del formulario si se envía mediante POST
        if ($_POST) {
            $usuario  = $_POST['usuario'];
            $password = $_POST['password'];

            // Validación de credenciales
            if ($model->validarLogin($usuario, $password)) {
                // Credenciales correctas → redirección al inicio
                miredirect('/');
            } else {
                // Credenciales incorrectas → mostrar formulario con error
                return view('login', [
                    'error' => 'Credenciales inválidas.'
                ]);
            }
        }

        // Mostrar formulario de login (GET)
        return view('login');
    }

    /**
     * @brief Cierra la sesión del usuario autenticado.
     *
     * Este método:
     * - Llama al método {@see Sesion::logout()} para destruir la sesión.
     * - Redirige al usuario al formulario de inicio de sesión.
     *
     * @return void
     */
    public function logout()
    {
        $model = Sesion::getInstance();
        $model->logout();

        miredirect('login');
    }
}
