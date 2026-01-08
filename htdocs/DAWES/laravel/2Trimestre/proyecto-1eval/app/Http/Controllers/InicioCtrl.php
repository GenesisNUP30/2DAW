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
class InicioCtrl
{

    /**
     * Muestra la página principal con la lista de tareas.
     *
     * Verifica que el usuario esté logueado antes de mostrar la lista.
     * Obtiene todas las tareas mediante el modelo Tareas y las pasa
     * a la vista 'index'. 
     * Pagina la lista de tareas y se añade un filtro de tareas pendientes.
     *
     * @return \Illuminate\View\View Retorna la vista 'index' con el array de tareas
     */
    public function index()
    {
        $login = Sesion::getInstance();
        $login->onlyLogged();

        $pagina = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $porPagina = 3;

        // Detectamos si se quiere mostrar solo las tareas pendientes
        $soloPendientes = (isset($_GET['pendientes']) && $_GET['pendientes'] == '1');

        $modelo = new Tareas();
        $resultado = $modelo->listarTareasPaginadas($pagina, $porPagina, $soloPendientes);

        $totalPaginas = (int) ceil($resultado['total'] / $porPagina);

        return view('index', [
            'tareas'        => $resultado['tareas'],
            'paginaActual'  => $pagina,
            'totalPaginas'  => $totalPaginas,
            'soloPendientes' => $soloPendientes
        ]);
    }


    /**
     * Muestra los detalles de una tarea específica.
     *
     * Verifica que el usuario esté logueado
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


        $modelo = new Tareas();
        $tarea = $modelo->obtenerTareaPorId($id);

        if (!$tarea) {
            abort(404, "Tarea no encontrada");
        }

        return view('tareadetalle', ['tarea' => $tarea]);
    }

    /**
     * Nos permite descargar un fichero adjunto a una tarea.
     * 
     * Verifica que el usuario esté logueado
     * Obtiene la tarea por su ID mediante el modelo Tareas
     * Si la tarea no existe o no tiene fichero, lanza un error 404.
     * 
     * @param int $id ID de la tarea a descargar el fichero
     * @return \Illuminate\Http\Response Retorna la respuesta de descarga del fichero
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException Si la tarea no existe o no tiene fichero
     */
    public function descargarFichero($id)
    {
        $login = Sesion::getInstance();
        $login->onlyLogged();

        $modelo = new Tareas();
        $tarea = $modelo->obtenerTareaPorId($id);

        if (!$tarea) {
            abort(404, "Tarea no encontrada");
        }

        if (!$tarea['fichero_resumen']) {
            abort(404, "No hay fichero adjunto");
        }

        $rutaFichero = storage_path('app/pruebas_tareas/' . $tarea['fichero_resumen']);

        if (!file_exists($rutaFichero)) {
            abort(404, 'El fichero no existe en el servidor');
        }

        return response()->download($rutaFichero);
    }
}
