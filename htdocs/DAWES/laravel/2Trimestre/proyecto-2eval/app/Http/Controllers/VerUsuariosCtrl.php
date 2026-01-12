<?php
/**
 * @file VerUsuariosCtrl.php
 *
 * @brief Controlador encargado de mostrar el listado de usuarios del sistema.
 *
 * Este controlador gestiona la visualización de todos los usuarios registrados,
 * verificando previamente que el usuario esté autenticado y tenga permisos
 * de administrador.
 */

namespace App\Http\Controllers;

use App\Models\Usuarios;
use App\Models\Sesion;

/**
 * @class VerUsuariosCtrl
 *
 * @brief Controlador para la visualización del listado de usuarios.
 *
 * Solo los usuarios con rol de administrador pueden acceder
 * a este controlador.
 */
class VerUsuariosCtrl
{
    /**
     * @brief Muestra el listado completo de usuarios.
     *
     * Este método:
     * - Verifica que el usuario esté logueado.
     * - Comprueba que el usuario tenga rol de administrador.
     * - Obtiene la lista completa de usuarios desde el modelo.
     * - Devuelve la vista `listarusuarios` con los datos obtenidos.
     *
     * @return \Illuminate\View\View
     * Devuelve la vista `listarusuarios` con el array de usuarios.
     */
    public function index()
    {
        /** @var Sesion $login Instancia única del sistema de sesiones */
        $login = Sesion::getInstance();

        // Comprobar autenticación
        $login->onlyLogged();

        // Comprobar permisos de administrador
        $login->onlyAdministrador();

        /** @var Usuarios $modelo Modelo encargado de la gestión de usuarios */
        $modelo = new Usuarios();

        /**
         * @var array $usuarios
         * Array con el listado completo de usuarios obtenidos desde el modelo.
         */
        $usuarios = $modelo->listarUsuarios();

        // Retornar la vista con los usuarios
        return view('listarusuarios', ['usuarios' => $usuarios]);
    }
}
