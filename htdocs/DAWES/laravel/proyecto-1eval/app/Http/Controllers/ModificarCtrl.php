<?php

namespace App\Http\Controllers;

use App\Models\Funciones;
use App\Models\Sesion;
use App\Models\Tareas;

/**
 * Controlador para modificar tareas existentes.
 *
 * Permite mostrar el formulario de modificación de una tarea y
 * actualizar los datos en la base de datos, con validación
 * de todos los campos requeridos.
 *
 * @package App\Http\Controllers
 */
class ModificarCtrl
{
    /**
     * Muestra el formulario de modificación de una tarea.
     *
     * Verifica que el usuario esté logueado y sea administrador.
     * Obtiene los datos de la tarea por su ID y los pasa a la vista.
     * Si la tarea no existe, retorna un error 404.
     *
     * @param int $id ID de la tarea a modificar
     * @return \Illuminate\View\View Retorna la vista 'modificar' con los datos de la tarea
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException Si la tarea no existe
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
     * Actualiza los datos de una tarea existente.
     *
     * Verifica la existencia de la tarea, valida los datos del formulario
     * y, si todo es correcto, actualiza la tarea en la base de datos.
     * Si hay errores, vuelve a mostrar la vista con los datos introducidos.
     *
     * @param int $id ID de la tarea a actualizar
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse Retorna la vista con errores o redirige a la página principal
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException Si la tarea no existe
     */
    public function actualizar($id)
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
                // Si hay errores, mostrar vista con datos del formulario
                return view('modificar', array_merge($_POST, ['id' => $id]));
            } else {
                // Si no hay errores, actualizar tarea y redirigir
                $modelo->actualizarTarea($id, $_POST);
                miredirect('/');
            }
        }
    }

    /**
     * Filtra y valida los datos del formulario de modificación de tarea.
     *
     * Valida los campos obligatorios: NIF/CIF, persona de contacto,
     * teléfono, correo, descripción, provincia y fecha de realización.
     * Almacena los errores en Funciones::$errores con clave del campo
     * y mensaje de error como valor.
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
            Funciones::$errores['nif_cif'] = "Debe introducir el NIF/CIF de la persona encargada de la tarea";
        } else {
            $resultado = Funciones::validarNif($nif_cif);
            if ($resultado !== true) {
                Funciones::$errores['nif_cif'] = $resultado;
            }
        }

        if ($persona_contacto === "") {
            Funciones::$errores['persona_contacto'] = "Debe introducir el nombre de la persona encargada de la tarea";
        }

        if ($descripcion === "") {
            Funciones::$errores['descripcion'] = "Debe introducir la descripción de la tarea";
        }

        if ($correo === "") {
            Funciones::$errores['correo'] = "Debe introducir el correo de la persona encargada de la tarea";
        } else if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            Funciones::$errores['correo'] = "El correo introducido no es válido";
        }

        if ($telefono == "") {
            Funciones::$errores['telefono'] = "Debe introducir el teléfono de la persona encargada de la tarea";
        } else {
            $resultado = Funciones::telefonoValido($telefono);
            if ($resultado !== true) {
                Funciones::$errores['telefono'] = $resultado;
            }
        }

        if ($codigo_postal != "" && !preg_match("/^[0-9]{5}$/", $codigo_postal)) {
            Funciones::$errores['codigo_postal'] = "El código postal introducido no es válido, debe tener 5 números";
        }

        if ($provincia === "") {
            Funciones::$errores['provincia'] = "Debe introducir la provincia";
        }

        $fechaActual = date('Y-m-d');
        if ($fecha_realizacion == "") {
            Funciones::$errores['fecha_realizacion'] = "Debe introducir la fecha de realización de la tarea";
        } else if ($fecha_realizacion <= $fechaActual) {
            Funciones::$errores['fecha_realizacion'] = "La fecha de realización debe ser posterior a la fecha actual";
        }
    }
}
