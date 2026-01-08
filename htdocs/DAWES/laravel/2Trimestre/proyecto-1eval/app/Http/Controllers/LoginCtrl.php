<?php
namespace App\Http\Controllers;

use App\Models\Sesion;

/**
 * Controlador encargado de gestionar el inicio y cierre de sesión del sistema.
 *
 * Proporciona las funciones necesarias para mostrar el formulario de login,
 * validar las credenciales de acceso y cerrar la sesión del usuario.
 *
 * @package App\Http\Controllers
 */
class LoginCtrl
{
    /**
     * Muestra el formulario de login o procesa los datos enviados.
     *
     * Si el usuario ya ha iniciado sesión, lo redirige al inicio.
     * Si se envía el formulario vía POST, valida el usuario y contraseña.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     *          Devuelve la vista del formulario de login o redirige al inicio.
     */
    public function login()
    {
        $model = Sesion::getInstance();

        // Si el usuario ya está logueado, se evita mostrar el formulario y se redirige al inicio
        if ($model->isLogged()) {
            miredirect('/');
        }

        // Si el formulario se envía mediante POST, procesamos los datos
        if ($_POST) {
            $usuario = $_POST['usuario'];
            $password = $_POST['password'];

            // Validación de credenciales
            if ($model->validarLogin($usuario, $password)) {
                // Credenciales correctas → redirección al inicio
                miredirect('/');
            } else {
                // Credenciales incorrectas → devolver vista con mensaje de error
                return view('login', ['error' => 'Credenciales inválidas.']);
            }
        }

        // Si no hay POST, simplemente se muestra el formulario de login
        return view('login');
    }

    /**
     * Cierra la sesión del usuario.
     *
     * Llama al método logout() del modelo Sesion y redirige al formulario de login.
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
