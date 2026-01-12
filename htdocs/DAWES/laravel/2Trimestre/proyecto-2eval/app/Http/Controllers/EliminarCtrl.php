<?php

namespace App\Http\Controllers;

use App\Models\Sesion;
use App\Models\Tareas;

/**
 * @class EliminarCtrl
 *
 * @brief Controlador responsable de la eliminación de tareas.
 *
 * Este controlador gestiona el proceso completo de borrado de tareas:
 * - Verificación de autenticación del usuario.
 * - Control de permisos (solo administradores).
 * - Visualización de la pantalla de confirmación de eliminación.
 * - Eliminación definitiva de la tarea de la base de datos.
 *
 * El proceso se divide en dos pasos para evitar eliminaciones accidentales:
 * confirmación previa y eliminación final.
 *
 * @package App\Http\Controllers
 */
class EliminarCtrl
{
    /**
     * @brief Muestra la vista de confirmación para eliminar una tarea.
     *
     * Este método:
     * - Verifica que el usuario esté autenticado.
     * - Verifica que el usuario tenga rol de administrador.
     * - Recupera la tarea asociada al identificador recibido.
     * - Envía los datos de la tarea a la vista `eliminar`.
     *
     * Si la tarea no existe, se devuelve una respuesta HTTP 404.
     *
     * @param int $id Identificador único de la tarea a eliminar.
     *
     * @return \Illuminate\View\View
     * Devuelve la vista `eliminar` con los datos de la tarea.
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * Se lanza cuando la tarea no existe.
     */
    public function confirmar($id)
    {
        // Control de acceso: usuario autenticado y administrador
        $login = Sesion::getInstance();
        $login->onlyLogged();
        $login->onlyAdministrador();

        // Obtención de la tarea
        $modelo = new Tareas();
        $tarea = $modelo->obtenerTareaPorId($id);

        // Comprobación de existencia
        if (!$tarea) {
            abort(404, 'Tarea no encontrada');
        }

        return view('eliminar', ['tarea' => $tarea]);
    }

    /**
     * @brief Elimina definitivamente una tarea del sistema.
     *
     * Este método:
     * - Invoca el método correspondiente del modelo {@see Tareas}
     *   para eliminar la tarea por su identificador.
     * - Redirige al usuario a la página principal tras completar la operación.
     *
     * @param int $id Identificador único de la tarea a eliminar.
     *
     * @return void
     */
    public function eliminar($id)
    {
        $modelo = new Tareas();
        $modelo->eliminarTarea($id);

        // Redirección tras la eliminación
        miredirect('/');
    }
}
