<?php
/**
 * @file EliminarUsuarioCtrl.php
 * @brief Controlador encargado de la eliminación de usuarios en el sistema.
 *
 * Gestiona:
 * - Confirmación de eliminación de un usuario.
 * - Eliminación del usuario en la base de datos.
 *
 * Solo los administradores pueden eliminar usuarios.
 */

namespace App\Http\Controllers;

use App\Models\Usuarios;
use App\Models\Sesion;

/**
 * @class EliminarUsuarioCtrl
 * @brief Controlador para la eliminación de usuarios.
 *
 * Se encarga de la confirmación y eliminación de un usuario
 * verificando que el usuario logueado tenga permisos de administrador.
 */
class EliminarUsuarioCtrl
{
    /**
     * @brief Muestra la página de confirmación para eliminar un usuario.
     *
     * Verifica que el usuario logueado sea administrador antes de mostrar el formulario.
     *
     * @param int $id ID del usuario a eliminar.
     * @return mixed Devuelve la vista 'eliminarusuario' con los datos del usuario.
     */
    public function confirmar($id)
    {
        $login = Sesion::getInstance();
        $login->onlyLogged();
        $login->onlyAdministrador();
        
        $modelo = new Usuarios();
        $usuario = $modelo->obtenerUsuarioPorId($id);

        if (!$usuario) {
            abort(404, 'Usuario no encontrado');
        }
        
        return view('eliminarusuario', ['usuario' => $usuario]);
    }

    /**
     * @brief Elimina un usuario de la base de datos.
     *
     * Solo un administrador puede ejecutar esta acción.
     * Redirige a la página principal tras la eliminación.
     *
     * @param int $id ID del usuario a eliminar.
     * @return void Redirige a la página principal después de eliminar.
     */
    public function eliminar($id)
    {
        $modelo = new Usuarios();
        $modelo->eliminarUsuario($id);
        miredirect('/');
    }
}
