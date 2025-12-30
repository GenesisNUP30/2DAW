<?php
namespace App\Http\Controllers;

use App\Models\Sesion;
use App\Models\Tareas;
use Illuminate\Support\Facades\Log;

/**
 * Controlador principal del proyecto.
 *
 * Gestiona la visualización de la página de inicio con la lista de tareas
 * y permite ver los detalles de una tarea específica.
 *
 * @package App\Http\Controllers
 */
class InicioCtrl {

    /**
     * Muestra la página principal con la lista de tareas.
     *
     * Verifica que el usuario esté logueado antes de mostrar la lista.
     * Obtiene todas las tareas mediante el modelo Tareas y las pasa
     * a la vista 'index'. Se registra un log de depuración con las tareas.
     *
     * @return \Illuminate\View\View Retorna la vista 'index' con el array de tareas
     */
    public function index()
    {
        $login = Sesion::getInstance();
        $login->onlyLogged();

        $modelo = new Tareas();
        $tareas = $modelo->listarTareas();
        Log::debug("InicioCtrl::index\n".print_r($tareas, true));
        
        return view('index', ['tareas' => $tareas]);
    }

    /**
     * Muestra los detalles de una tarea específica.
     *
     * Verifica que el usuario esté logueado y sea administrador.
     * Obtiene la tarea por su ID mediante el modelo Tareas y la pasa
     * a la vista 'tareadetalle'. Si la tarea no existe, lanza un error 404.
     *
     * @param int $id ID de la tarea a mostrar
     * @return \Illuminate\View\View Retorna la vista 'tareadetalle' con los datos de la tarea
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException Si la tarea no existe
     */
    public function verTarea($id)
    {
        $login = Sesion::getInstance();
        $login->onlyLogged();
        // $login->onlyAdministrador();

        $modelo = new Tareas();
        $tarea = $modelo->obtenerTareaPorId($id);

        if (!$tarea) {
            abort(404, "Tarea no encontrada");
        }

        return view('tareadetalle', ['tarea' => $tarea]);
    }
}
