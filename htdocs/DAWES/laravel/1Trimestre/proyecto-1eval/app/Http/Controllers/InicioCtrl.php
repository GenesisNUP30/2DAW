<?php

namespace App\Http\Controllers;

use App\Models\Sesion;
use App\Models\Tareas;
use App\Models\ConfigAvanzada;
use Illuminate\Support\Facades\Log;

/**
 * @class InicioCtrl
 *
 * @brief Controlador principal de la aplicación.
 *
 * Este controlador gestiona las funcionalidades principales accesibles
 * tras la autenticación del usuario:
 * - Visualización de la página de inicio con el listado de tareas.
 * - Paginación y filtrado de tareas pendientes.
 * - Visualización del detalle de una tarea concreta.
 * - Descarga de ficheros adjuntos asociados a tareas.
 *
 * Todas las acciones de este controlador requieren que el usuario
 * esté correctamente autenticado.
 *
 * @package App\Http\Controllers
 */
class InicioCtrl
{
    /**
     * @brief Muestra la página principal con el listado de tareas.
     *
     * Este método:
     * - Verifica que el usuario esté autenticado.
     * - Gestiona la paginación del listado de tareas.
     * - Permite filtrar las tareas para mostrar únicamente las pendientes.
     * - Recupera las tareas desde el modelo {@see Tareas}.
     * - Envía los datos necesarios a la vista `index`.
     *
     * La paginación se controla mediante parámetros GET:
     * - `page`: número de página actual.
     * - `pendientes`: filtra solo tareas pendientes cuando su valor es `1`.
     *
     * @return \Illuminate\View\View
     * Devuelve la vista `index` con la lista de tareas y datos de paginación.
     */
    public function index()
    {
        // Control de acceso: usuario autenticado
        $login = Sesion::getInstance();
        $login->onlyLogged();

        // Gestión de paginación
        $pagina = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
        $cofiguracion = ConfigAvanzada::getInstance();
        $porPagina = (int) $cofiguracion->get('items_por_pagina', 5);

        // Filtro de tareas pendientes
        $soloPendientes = (isset($_GET['pendientes']) && $_GET['pendientes'] == '1');

        // Obtención de tareas paginadas
        $modelo = new Tareas();
        $resultado = $modelo->listarTareasPaginadas($pagina, $porPagina, $soloPendientes);

        // Cálculo del número total de páginas
        $totalPaginas = (int) ceil($resultado['total'] / $porPagina);

        return view('index', [
            'tareas'         => $resultado['tareas'],
            'paginaActual'   => $pagina,
            'totalPaginas'   => $totalPaginas,
            'soloPendientes' => $soloPendientes
        ]);
    }

    /**
     * @brief Muestra el detalle de una tarea concreta.
     *
     * Este método:
     * - Verifica que el usuario esté autenticado.
     * - Recupera la tarea correspondiente al identificador recibido.
     * - Envía los datos de la tarea a la vista `tareadetalle`.
     *
     * Si la tarea no existe, se devuelve una respuesta HTTP 404.
     *
     * @param int $id Identificador único de la tarea a visualizar.
     *
     * @return \Illuminate\View\View
     * Devuelve la vista `tareadetalle` con los datos completos de la tarea.
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * Se lanza cuando la tarea solicitada no existe.
     */
    public function verTarea($id)
    {
        // Control de acceso: usuario autenticado
        $login = Sesion::getInstance();
        $login->onlyLogged();

        // Obtención de la tarea
        $modelo = new Tareas();
        $tarea = $modelo->obtenerTareaPorId($id);

        // Comprobación de existencia
        if (!$tarea) {
            abort(404, "Tarea no encontrada");
        }

        return view('tareadetalle', ['tarea' => $tarea]);
    }

    /**
     * @brief Permite descargar el fichero adjunto asociado a una tarea.
     *
     * Este método:
     * - Verifica que el usuario esté autenticado.
     * - Recupera la tarea correspondiente al identificador recibido.
     * - Comprueba que la tarea tenga un fichero adjunto.
     * - Verifica la existencia física del fichero en el servidor.
     * - Devuelve la respuesta de descarga del fichero.
     *
     * Si la tarea no existe, no tiene fichero adjunto o el fichero no
     * se encuentra en el sistema de archivos, se devuelve un error HTTP 404.
     *
     * @param int $id Identificador único de la tarea cuyo fichero se desea descargar.
     *
     * @return \Illuminate\Http\Response
     * Devuelve la respuesta HTTP que inicia la descarga del fichero.
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * Se lanza cuando la tarea o el fichero asociado no existen.
     */
    public function descargarFichero($id)
    {
        // Control de acceso: usuario autenticado
        $login = Sesion::getInstance();
        $login->onlyLogged();

        // Obtención de la tarea
        $modelo = new Tareas();
        $tarea = $modelo->obtenerTareaPorId($id);

        // Validación de existencia de la tarea
        if (!$tarea) {
            abort(404, "Tarea no encontrada");
        }

        // Validación de existencia de fichero adjunto
        if (!$tarea['fichero_resumen']) {
            abort(404, "No hay fichero adjunto");
        }

        $rutaFichero = storage_path('app/pruebas_tareas/' . $tarea['fichero_resumen']);

        // Validación de existencia física del fichero
        if (!file_exists($rutaFichero)) {
            abort(404, 'El fichero no existe en el servidor');
        }

        return response()->download($rutaFichero);
    }
}
