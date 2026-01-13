<?php

namespace App\Http\Controllers;

use App\Models\Funciones;
use App\Models\Sesion;
use App\Models\Tareas;

/**
 * @class ModificarCtrl
 *
 * @brief Controlador encargado de la modificación de tareas existentes.
 *
 * Este controlador gestiona el proceso completo de edición de una tarea:
 * - Visualización del formulario de modificación.
 * - Validación de los datos introducidos por el usuario.
 * - Actualización de la tarea en la base de datos.
 *
 * Solo los usuarios autenticados con rol de administrador
 * pueden acceder a estas funcionalidades.
 *
 * @package App\Http\Controllers
 */
class ModificarCtrl
{
    /**
     * @brief Muestra el formulario de modificación de una tarea.
     *
     * Este método:
     * - Verifica que el usuario esté autenticado.
     * - Verifica que el usuario tenga permisos de administrador.
     * - Obtiene los datos de la tarea a partir de su identificador.
     * - Envía los datos a la vista `modificar`.
     *
     * Si la tarea no existe, se lanza una excepción HTTP 404.
     *
     * @param int $id Identificador único de la tarea a modificar.
     *
     * @return \Illuminate\View\View
     * Devuelve la vista `modificar` con los datos de la tarea.
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * Se lanza si la tarea no existe.
     */
    public function mostrarFormulario($id)
    {
        $login = Sesion::getInstance();
        $login->onlyLogged();
        $login->onlyAdministrador();

        $modelo = new Tareas();
        $tarea = $modelo->obtenerTareaPorId($id);

        if (!$tarea) {
            abort(404, 'Tarea no encontrada');
        }

        return view('modificar', $tarea);
    }

    /**
     * @brief Actualiza los datos de una tarea existente.
     *
     * Este método:
     * - Comprueba que la tarea exista.
     * - Filtra y valida los datos enviados mediante POST.
     * - Si existen errores de validación, vuelve a mostrar el formulario
     *   con los datos introducidos y los mensajes de error.
     * - Si los datos son correctos, actualiza la tarea y redirige
     *   a la página principal.
     *
     * @param int $id Identificador único de la tarea a actualizar.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     * Devuelve la vista `modificar` con errores o redirige al inicio.
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * Se lanza si la tarea no existe.
     */
    public function actualizar($id)
    {
        if ($_POST) {
            $modelo = new Tareas();
            $tarea = $modelo->obtenerTareaPorId($id);

            if (!$tarea) {
                abort(404, 'Tarea no encontrada');
            }

            // Inicialización del array de errores
            Funciones::$errores = [];

            // Validación de datos del formulario
            $this->filtraDatos();

            if (!empty(Funciones::$errores)) {
                // Existen errores → volver a mostrar el formulario
                return view('modificar', array_merge($_POST, ['id' => $id]));
            } else {
                // Datos correctos → actualizar y redirigir
                $modelo->actualizarTarea($id, $_POST);
                miredirect('/');
            }
        }
    }

    /**
     * @brief Filtra y valida los datos del formulario de modificación de tareas.
     *
     * Valida los campos obligatorios:
     * - NIF/CIF
     * - Persona de contacto
     * - Teléfono
     * - Correo electrónico
     * - Descripción de la tarea
     * - Provincia
     * - Fecha de realización
     *
     * Los errores detectados se almacenan en el array estático
     * {@see Funciones::$errores}, usando como clave el nombre del campo
     * y como valor el mensaje de error correspondiente.
     *
     * @return void
     */
    private function filtraDatos()
    {
        Funciones::$errores = [];

        $nif_cif           = $_POST['nif_cif'] ?? '';
        $persona_contacto  = $_POST['persona_contacto'] ?? '';
        $telefono          = $_POST['telefono'] ?? '';
        $descripcion       = $_POST['descripcion'] ?? '';
        $correo            = $_POST['correo'] ?? '';
        $codigo_postal     = $_POST['codigo_postal'] ?? '';
        $provincia         = $_POST['provincia'] ?? '';
        $fecha_realizacion = $_POST['fecha_realizacion'] ?? '';

        if ($nif_cif === "") {
            Funciones::$errores['nif_cif'] =
                "Debe introducir el NIF/CIF de la persona encargada de la tarea";
        } else {
            $resultado = Funciones::validarNif($nif_cif);
            if ($resultado !== true) {
                Funciones::$errores['nif_cif'] = $resultado;
            }
        }

        if ($persona_contacto === "") {
            Funciones::$errores['persona_contacto'] =
                "Debe introducir el nombre de la persona encargada de la tarea";
        }

        if ($descripcion === "") {
            Funciones::$errores['descripcion'] =
                "Debe introducir la descripción de la tarea";
        }

        if ($correo === "") {
            Funciones::$errores['correo'] =
                "Debe introducir el correo de la persona encargada de la tarea";
        } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            Funciones::$errores['correo'] =
                "El correo introducido no es válido";
        }

        if ($telefono === "") {
            Funciones::$errores['telefono'] =
                "Debe introducir el teléfono de la persona encargada de la tarea";
        } else {
            $resultado = Funciones::telefonoValido($telefono);
            if ($resultado !== true) {
                Funciones::$errores['telefono'] = $resultado;
            }
        }

        if ($codigo_postal !== "" && !preg_match("/^[0-9]{5}$/", $codigo_postal)) {
            Funciones::$errores['codigo_postal'] =
                "El código postal introducido no es válido, debe tener 5 números";
        }

        if ($provincia === "") {
            Funciones::$errores['provincia'] =
                "Debe introducir la provincia";
        }

        $fechaActual = date('Y-m-d');
        if ($fecha_realizacion === "") {
            Funciones::$errores['fecha_realizacion'] =
                "Debe introducir la fecha de realización de la tarea";
        } elseif ($fecha_realizacion <= $fechaActual) {
            Funciones::$errores['fecha_realizacion'] =
                "La fecha de realización debe ser posterior a la fecha actual";
        }
    }
}
