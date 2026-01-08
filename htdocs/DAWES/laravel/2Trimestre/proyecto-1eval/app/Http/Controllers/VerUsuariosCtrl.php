<?php
/**
 * @file VerUsuariosCtrl.php
 * @brief Controlador encargado de mostrar el listado de usuarios.
 *
 * Este controlador gestiona la visualización de todos los usuarios del sistema.
 * Incluye validaciones de sesión y permisos, utilizando el modelo Sesion.
 */

namespace App\Http\Controllers;

use App\Models\Usuarios;
use App\Models\Sesion;

/**
 * @class VerUsuariosCtrl
 * @brief Controlador para listar usuarios.
 *
 * Verifica que el usuario esté autenticado y tenga rol de administrador antes
 * de permitir el acceso al listado de usuarios.
 */
class VerUsuariosCtrl
{
    /**
     * @brief Método principal del controlador.
     *
     * Realiza tres tareas:
     * - Comprueba si el usuario está logueado.
     * - Comprueba si el usuario tiene permisos de administrador.
     * - Solicita al modelo la lista de usuarios y devuelve la vista correspondiente.
     *
     * @return mixed Devuelve una vista con el listado de usuarios.
     */
    public function index()
    {
        /** @var Sesion $login Instancia única del sistema de sesiones */
        $login = Sesion::getInstance();

        // Asegurar que el usuario está logueado
        $login->onlyLogged();

        // Asegurar que el usuario es administrador
        $login->onlyAdministrador();

        /** @var Usuarios $modelo Modelo que gestiona los usuarios */
        $modelo = new Usuarios();

        /** 
         * @var array $usuarios Lista completa de usuarios obtenidos desde el modelo.
         */
        $usuarios = $modelo->listarUsuarios();

        // Retornar la vista pasando los usuarios
        return view('listarusuarios', ['usuarios' => $usuarios]);
    }
}
