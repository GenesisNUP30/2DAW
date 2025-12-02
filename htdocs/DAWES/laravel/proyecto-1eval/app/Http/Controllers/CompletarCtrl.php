<?php

namespace App\Http\Controllers;

use App\Models\Funciones;
use App\Models\Sesion;
use App\Models\Tareas;

/**
 * Controlador para completar tareas existentes.
 *
 * Permite a un operario actualizar el estado de una tarea y añadir
 * anotaciones posteriores. Se asegura de que el usuario esté logueado
 * y tenga permisos de operario.
 *
 * @package App\Http\Controllers
 */
class CompletarCtrl
{
    /**
     * Muestra el formulario para completar una tarea.
     *
     * Verifica que el usuario esté logueado y sea operario.
     * Obtiene la tarea por su ID y la pasa a la vista.
     * Si la tarea no existe, retorna un error 404.
     *
     * @param int $id ID de la tarea a completar
     * @return \Illuminate\View\View Retorna la vista 'completar' con los datos de la tarea
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException Si la tarea no existe
     */
    public function mostrarFormulario($id)
    {
        $login = Sesion::getInstance();
        $login->onlyLogged();
        $login->onlyOperario();

        $modelo = new Tareas();
        $tarea = $modelo->obtenerTareaPorId($id);

        if (!$tarea) {
            abort(404, 'Tarea no encontrada');
        }

        return view('completar', $tarea);
    }

    /**
     * Completa la tarea con el estado y las anotaciones posteriores.
     *
     * Valida los datos enviados por el formulario, y si no hay errores,
     * actualiza la tarea en la base de datos mediante el modelo Tareas.
     * Redirige a la página principal después de completar la tarea.
     *
     * @param int $id ID de la tarea a completar
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse Retorna la vista con errores o redirige a la página principal
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException Si la tarea no existe
     */
    public function completar($id)
    {
        if ($_POST) {
            $modelo = new Tareas();
            $tarea = $modelo->obtenerTareaPorId($id);

            if (!$tarea) {
                abort(404, 'Tarea no encontrada');
            }

            Funciones::$errores = [];
            $this->filtraDatos();

            if (!empty(Funciones::$errores)) {
                return view('completar', array_merge($_POST, ['id' => $id]));
            }

            $datos = [
                'estado' => $_POST['estado'],
                'anotaciones_posteriores' => $_POST['anotaciones_posteriores'],
            ];
            $modelo->completarTarea($id, $datos);
            miredirect('/');
        }
    }

    /**
     * Filtra y valida los datos del formulario de completar tarea.
     *
     * Valida que el campo 'estado' y 'anotaciones_posteriores' no estén vacíos.
     * Almacena los errores en Funciones::$errores con clave del campo y mensaje de error.
     *
     * @return void
     */
    private function filtraDatos()
    {
        extract($_POST);

        if ($estado === "") {
            Funciones::$errores['estado'] = "Debe seleccionar el estado de la tarea";
        }

        if ($anotaciones_posteriores === "") {
            Funciones::$errores['anotaciones_posteriores'] = "Debe introducir las anotaciones posteriores";
        }
    }
}
