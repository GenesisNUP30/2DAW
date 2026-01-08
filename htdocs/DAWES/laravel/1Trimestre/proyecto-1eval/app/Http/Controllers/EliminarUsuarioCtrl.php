<?php
/**
 * @file EliminarUsuarioCtrl.php
 *
 * @brief Controlador encargado de la eliminación de usuarios del sistema.
 *
 * Este controlador gestiona el proceso completo de borrado de usuarios:
 * - Visualización de la pantalla de confirmación de eliminación.
 * - Eliminación definitiva del usuario en la base de datos.
 *
 * Por motivos de seguridad, **únicamente los usuarios con rol de administrador**
 * pueden ejecutar estas acciones.
 */

namespace App\Http\Controllers;

use App\Models\Usuarios;
use App\Models\Sesion;

/**
 * @class EliminarUsuarioCtrl
 *
 * @brief Controlador responsable de la eliminación de usuarios.
 *
 * Implementa un flujo de eliminación en dos pasos (confirmación y borrado)
 * para evitar eliminaciones accidentales, garantizando además que
 * el usuario autenticado tenga permisos de administrador.
 *
 * @package App\Http\Controllers
 */
class EliminarUsuarioCtrl
{
    /**
     * @brief Muestra la vista de confirmación para eliminar un usuario.
     *
     * Este método:
     * - Verifica que el usuario esté autenticado.
     * - Verifica que el usuario tenga rol de administrador.
     * - Recupera los datos del usuario a eliminar.
     * - Envía la información a la vista `eliminarusuario`.
     *
     * Si el usuario no existe, se devuelve una respuesta HTTP 404.
     *
     * @param int $id Identificador único del usuario a eliminar.
     *
     * @return \Illuminate\View\View
     * Devuelve la vista `eliminarusuario` con los datos del usuario.
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * Se lanza cuando el usuario no existe.
     */
    public function confirmar($id)
    {
        // Control de acceso: usuario autenticado y administrador
        $login = Sesion::getInstance();
        $login->onlyLogged();
        $login->onlyAdministrador();

        // Obtención del usuario
        $modelo = new Usuarios();
        $usuario = $modelo->obtenerUsuarioPorId($id);

        // Comprobación de existencia
        if (!$usuario) {
            abort(404, 'Usuario no encontrado');
        }

        return view('eliminarusuario', ['usuario' => $usuario]);
    }

    /**
     * @brief Elimina definitivamente un usuario del sistema.
     *
     * Este método:
     * - Invoca el método correspondiente del modelo {@see Usuarios}
     *   para eliminar el usuario identificado por su ID.
     * - Redirige al usuario a la página principal tras completar la operación.
     *
     * Esta acción solo puede ser ejecutada por un administrador.
     *
     * @param int $id Identificador único del usuario a eliminar.
     *
     * @return void
     */
    public function eliminar($id)
    {
        $modelo = new Usuarios();
        $modelo->eliminarUsuario($id);

        // Redirección tras la eliminación
        miredirect('/');
    }
}
