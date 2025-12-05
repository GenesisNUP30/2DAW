<?php

namespace App\Http\Controllers;

use App\Models\Funciones;
use App\Models\Tareas;
use App\Models\Sesion;

/**
 * Controlador para dar de alta nuevas tareas en el sistema.
 *
 * Esta clase se encarga de gestionar la creación de tareas, 
 * validar los datos del formulario y redirigir al usuario
 * según el resultado de la operación.
 *
 * Nota: Es necesario desactivar CSRF en Laravel para permitir 
 * procesar el formulario correctamente.
 *
 * @package App\Http\Controllers
 */
class AltaCtrl
{
    /**
     * Método principal para gestionar la creación de tareas.
     *
     * Si se recibe un POST, filtra y valida los datos. En caso de errores,
     * devuelve la vista 'alta' con los datos introducidos y mensajes de error.
     * Si no hay errores, guarda la tarea en la base de datos y redirige a la página principal.
     *
     * Si no se recibe un POST, carga la vista 'alta' con campos vacíos por defecto.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse Retorna la vista de alta o redirige al listado.
     */
    public function alta()
    {
        // Obtiene la instancia de sesión
        $login = Sesion::getInstance();
        $login->onlyLogged();        // Permite solo usuarios logueados
        $login->onlyAdministrador(); // Permite solo administradores

        if ($_POST) {
            Funciones::$errores = [];
            // Filtra y valida los datos del formulario
            $this->filtraDatos();

            if (!empty(Funciones::$errores)) {
                // Si hay errores, muestra la vista con los datos del formulario
                return view('alta', $_POST);
            } else {
                // Si no hay errores, registra la tarea y redirige
                $model = new Tareas();
                $model->registraAlta($_POST);
                miredirect('/');
            }
        } else {
            // Datos iniciales vacíos para la vista
            $datos = [
                'nif_cif' => '',
                'persona_contacto' => '',
                'telefono' => "",
                'descripcion' => "",
                'correo' => "",
                'direccion' => "",
                'poblacion' => "",
                'codigo_postal' => "",
                'provincia' => "",
                'estado' => "",
                'operario_encargado' => "",
                'fecha_realizacion' => "",
                'anotaciones_anteriores' => "",
                'anotaciones_posteriores' => "",
            ];
            return view('alta', $datos);
        }
    }

    /**
     * Filtra y valida los datos del formulario de alta de tareas.
     *
     * Verifica que los campos obligatorios no estén vacíos,
     * valida NIF/CIF, correo electrónico y teléfono, y
     * asegura que la fecha de realización sea posterior a la actual.
     *
     * Los errores se almacenan en Funciones::$errores con el
     * nombre del campo como clave y el mensaje de error como valor.
     *
     * @return void
     */
    private function filtraDatos()
    {
        // Inicializa array de errores
        Funciones::$errores = [];

        // Recupera los datos del POST
        $nif_cif = $_POST['nif_cif'] ?? '';
        $persona_contacto = $_POST['persona_contacto'] ?? '';
        $telefono = $_POST['telefono'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $correo = $_POST['correo'] ?? '';
        $codigo_postal = $_POST['codigo_postal'] ?? '';
        $provincia = $_POST['provincia'] ?? '';
        $fecha_realizacion = $_POST['fecha_realizacion'] ?? '';

        // Validaciones por campo
        if ($nif_cif == "") {
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
        } else {
            if ($fecha_realizacion <= $fechaActual) {
                Funciones::$errores['fecha_realizacion'] = "La fecha de realización debe ser posterior a la fecha actual";
            }
        }
    }
}
