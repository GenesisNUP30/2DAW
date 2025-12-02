<?php
/**
 * @file AñadirUsuarioCtrl.php
 * @brief Controlador para añadir nuevos usuarios al sistema.
 *
 * Este controlador gestiona el formulario de creación de usuarios,
 * valida los datos enviados y delega al modelo correspondiente
 * la inserción del registro en la base de datos.
 */

namespace App\Http\Controllers;

use App\Models\Usuarios;
use App\Models\Sesion;
use App\Models\Funciones;

/**
 * @class AñadirUsuarioCtrl
 * @brief Controlador encargado de la lógica del alta de usuarios.
 *
 * Verifica permisos, valida datos recibidos por POST y envía la información
 * al modelo para registrar un nuevo usuario.
 */
class AñadirUsuarioCtrl
{
    /**
     * @brief Método que muestra el formulario o procesa el envío del mismo.
     *
     * Funciona en dos modos:
     * - **GET:** muestra el formulario vacío.
     * - **POST:** valida los datos, revisa errores y registra el usuario.
     *
     * Requiere:
     * - Tener sesión iniciada.
     * - Disponer del rol de administrador.
     *
     * @return mixed Vista con el formulario o redirección tras el registro.
     */
    public function añadirUsuario()
    {
        /** @var Sesion $login Instancia de Sesion para controlar permisos y estados */
        $login = Sesion::getInstance();
        $login->onlyLogged();          ///< Exige que el usuario esté logueado
        $login->onlyAdministrador();   ///< Exige que el usuario tenga rol de administrador

        // Si se ha enviado el formulario (REQUEST POST)
        if ($_POST) {

            // Reiniciamos los errores del validador
            Funciones::$errores = [];

            // Validación y filtrado de datos del formulario
            $this->filtraDatos();

            // Si hay errores → mostrar formulario con los datos introducidos
            if (!empty(Funciones::$errores)) {
                return view('añadirusuario', $_POST);
            } 
            else {
                // Si no hay errores → insertar usuario
                $modelo = new Usuarios();
                $modelo->registraUsuario($_POST);

                // Redirigir al listado de usuarios
                miredirect('listarusuarios');
            }

        } else {
            // Si NO se envió el formulario, se carga vacío
            $datos = [
                'usuario'  => '',
                'password' => '',
                'rol'      => '',
            ];

            return view('añadirusuario', $datos);
        }
    }

    /**
     * @brief Valida los datos enviados desde el formulario de alta.
     *
     * Realiza comprobaciones sobre:
     * - Usuario vacío
     * - Contraseña vacía
     * - Confirmación de contraseña vacía o distinta
     * - Rol vacío
     *
     * Almacena los errores dentro de `Funciones::$errores`.
     *
     * @return void No devuelve nada; solo actualiza la variable estática de errores.
     */
    public function filtraDatos()
    {
        // Extrae las variables desde $_POST al ámbito local
        extract($_POST);

        /** ------------------------- USUARIO ------------------------- */
        if ($usuario === "") {
            Funciones::$errores['usuario'] = "Debe introducir el nombre de usuario";
        }

        /** ------------------------- PASSWORD ------------------------ */
        if ($password === "") {
            Funciones::$errores['password'] = "Debe introducir la contraseña";
        }

        /** ------------------- CONFIRMAR PASSWORD -------------------- */
        if ($password2 === "") {
            Funciones::$errores['password2'] = "Debe confirmar la contraseña";
        } 
        elseif ($password !== $password2) {
            Funciones::$errores['password2'] = "Las contraseñas no coinciden";
        }

        /** ----------------------------- ROL -------------------------- */
        if ($rol === "") {
            Funciones::$errores['rol'] = "Debe seleccionar el rol";
        }
    }
}
