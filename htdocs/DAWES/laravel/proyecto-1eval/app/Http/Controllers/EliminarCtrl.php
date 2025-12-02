<?php
namespace App\Http\Controllers;

use App\Models\Sesion;
use App\Models\Tareas;

/**
 * Controlador para eliminar tareas.
 *
 * Permite mostrar una confirmación de eliminación y eliminar
 * una tarea de la base de datos. Se asegura de que solo usuarios
 * logueados y administradores puedan realizar la acción.
 *
 * @package App\Http\Controllers
 */
class EliminarCtrl
{
    /**
     * Muestra la página de confirmación de eliminación de una tarea.
     *
     * Verifica que el usuario esté logueado y sea administrador.
     * Obtiene la tarea por su ID y la pasa a la vista.
     * Si la tarea no existe, lanza un error 404.
     *
     * @param int $id ID de la tarea a eliminar
     * @return \Illuminate\View\View Retorna la vista 'eliminar' con los datos de la tarea
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException Si la tarea no existe
     */
    public function confirmar($id)
    {
        $login = Sesion::getInstance();
        $login->onlyLogged();
        $login->onlyAdministrador();

        $modelo = new Tareas();
        $tarea = $modelo->obtenerTareaPorId($id);

        if (!$tarea) {
            abort(404, 'Tarea no encontrada');
        }

        return view('eliminar', ['tarea' => $tarea]);
    }

    /**
     * Elimina una tarea de la base de datos.
     *
     * Llama al método del modelo Tareas para eliminar la tarea por ID
     * y redirige a la página principal.
     *
     * @param int $id ID de la tarea a eliminar
     * @return void Redirige a la página principal después de la eliminación
     */
    public function eliminar($id)
    {
        $modelo = new Tareas();
        $modelo->eliminarTarea($id);
        miredirect('/');
    }
}
