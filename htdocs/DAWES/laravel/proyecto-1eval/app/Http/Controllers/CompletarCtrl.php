<?php

namespace App\Http\Controllers;

use App\Models\Funciones;
use App\Models\Sesion;
use App\Models\Tareas;

/**
 * @class CompletarCtrl
 *
 * @brief Controlador responsable de la finalización y actualización de tareas.
 *
 * Este controlador permite a los usuarios con rol de operario:
 * - Visualizar el formulario de finalización de una tarea concreta.
 * - Actualizar el estado de la tarea.
 * - Registrar anotaciones posteriores al trabajo realizado.
 * - Adjuntar un fichero de prueba cuando la tarea se marca como realizada.
 *
 * El acceso a sus métodos está restringido a usuarios autenticados
 * con permisos de operario.
 *
 * La validación de los datos del formulario se apoya en la clase
 * {@see Funciones}, donde se centraliza la gestión de errores.
 *
 * @package App\Http\Controllers
 */
class CompletarCtrl
{
    /**
     * @brief Muestra el formulario de finalización de una tarea.
     *
     * Este método:
     * - Verifica que el usuario esté autenticado.
     * - Verifica que el usuario tenga rol de operario.
     * - Recupera la tarea correspondiente al identificador recibido.
     * - Envía los datos de la tarea a la vista `completar`.
     *
     * Si la tarea no existe, se devuelve una respuesta HTTP 404.
     *
     * @param int $id Identificador único de la tarea a completar.
     *
     * @return \Illuminate\View\View
     * Devuelve la vista `completar` con los datos completos de la tarea.
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * Se lanza cuando la tarea solicitada no existe.
     */
    public function mostrarFormulario($id)
    {
        // Control de acceso: usuario autenticado y operario
        $login = Sesion::getInstance();
        $login->onlyLogged();
        $login->onlyOperario();

        // Obtención de la tarea
        $modelo = new Tareas();
        $tarea = $modelo->obtenerTareaPorId($id);

        // Control de existencia de la tarea
        if (!$tarea) {
            abort(404, 'Tarea no encontrada');
        }

        return view('completar', $tarea);
    }

    /**
     * @brief Procesa la finalización de una tarea existente.
     *
     * Este método gestiona el envío del formulario de finalización:
     *
     * - Recupera la tarea por su identificador.
     * - Filtra y valida los datos enviados por el formulario.
     * - En caso de errores, recarga la vista con los mensajes correspondientes.
     * - Si la tarea se marca como realizada (`estado = 'R'`):
     *   - Verifica la subida obligatoria de un fichero de prueba.
     *   - Almacena el fichero en la carpeta `storage/app/pruebas_tareas`.
     *   - Genera un nombre seguro combinando el ID de la tarea y el nombre original.
     * - Actualiza la tarea en la base de datos mediante el modelo {@see Tareas}.
     *
     * Tras una actualización correcta, redirige a la página principal.
     *
     * @param int $id Identificador único de la tarea a completar.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     * Devuelve la vista de finalización con errores o redirige al inicio.
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * Se lanza cuando la tarea no existe.
     */
    public function completar($id)
    {
        if ($_POST) {

            // Recuperación de la tarea
            $modelo = new Tareas();
            $tarea = $modelo->obtenerTareaPorId($id);

            if (!$tarea) {
                abort(404, 'Tarea no encontrada');
            }

            // Inicialización del contenedor de errores
            Funciones::$errores = [];
            $this->filtraDatos();

            // Si hay errores, se recarga la vista
            if (!empty(Funciones::$errores)) {
                return view('completar', array_merge($_POST, ['id' => $id]));
            }

            $rutaFichero = null;

            // Gestión del fichero de prueba cuando la tarea se marca como realizada
            if ($_POST['estado'] === 'R') {

                if (
                    isset($_FILES['fichero_resumen']) &&
                    $_FILES['fichero_resumen']['error'] === UPLOAD_ERR_OK
                ) {
                    $carpeta = storage_path('app/pruebas_tareas');

                    // Creación de la carpeta si no existe
                    if (!is_dir($carpeta)) {
                        mkdir($carpeta, 0755, true);
                    }

                    // Generación de nombre seguro del fichero
                    $nombreSeguro = $id . '_' . basename($_FILES['fichero_resumen']['name']);
                    $rutaCompleta = $carpeta . '/' . $nombreSeguro;

                    // Almacenamiento del fichero en el sistema
                    move_uploaded_file(
                        $_FILES['fichero_resumen']['tmp_name'],
                        $rutaCompleta
                    );

                    $rutaFichero = $nombreSeguro;
                }
            }

            // Preparación de los datos a actualizar
            $datos = [
                'estado' => $_POST['estado'],
                'anotaciones_posteriores' => $_POST['anotaciones_posteriores'],
                'fecha_realizacion' => $_POST['fecha_realizacion'],
                'fichero_resumen' => $rutaFichero,
            ];

            // Actualización de la tarea
            $modelo->completarTarea($id, $datos);

            // Redirección tras la finalización correcta
            miredirect('/');
        }
    }

    /**
     * @brief Filtra y valida los datos del formulario de finalización de tareas.
     *
     * Este método comprueba:
     * - Que el estado de la tarea esté informado.
     * - Que existan anotaciones posteriores.
     * - Si la tarea se marca como realizada (`estado = 'R'`):
     *   - Que se haya indicado la fecha de realización.
     *   - Que se haya subido correctamente un fichero de prueba.
     *
     * Todos los errores se almacenan en {@see Funciones::$errores},
     * utilizando el nombre del campo como clave y el mensaje de error como valor.
     *
     * @return void
     */
    private function filtraDatos()
    {
        extract($_POST);

        // Validación del estado
        if ($estado === "") {
            Funciones::$errores['estado'] = "Debe seleccionar el estado de la tarea";
        }

        // Validación de las anotaciones posteriores
        if ($anotaciones_posteriores === "") {
            Funciones::$errores['anotaciones_posteriores'] = "Debe introducir las anotaciones posteriores";
        }

        // Validaciones adicionales solo si la tarea se marca como realizada
        if ($estado === "R") {

            if (empty($fecha_realizacion)) {
                Funciones::$errores['fecha_realizacion'] = "Debe introducir la fecha de realización";
            }

            if (
                !isset($_FILES['fichero_resumen']) ||
                $_FILES['fichero_resumen']['error'] !== UPLOAD_ERR_OK
            ) {
                Funciones::$errores['fichero_resumen'] = "Debe subir un fichero como prueba del trabajo";
            }
        }
    }
}
