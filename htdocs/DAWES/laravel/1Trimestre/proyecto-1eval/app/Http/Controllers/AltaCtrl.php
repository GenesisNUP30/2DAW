<?php

namespace App\Http\Controllers;

use App\Models\Funciones;
use App\Models\Tareas;
use App\Models\Sesion;

/**
 * @class AltaCtrl
 *
 * @brief Controlador responsable de la creación (alta) de nuevas tareas.
 *
 * Este controlador gestiona todo el flujo del alta de tareas dentro del sistema:
 * - Control de acceso (solo usuarios autenticados y administradores).
 * - Carga del formulario de alta.
 * - Filtrado y validación exhaustiva de los datos enviados por el usuario.
 * - Persistencia de la tarea en la base de datos.
 * - Gestión de errores y redirecciones según el resultado del proceso.
 *
 * La validación de datos se apoya en métodos auxiliares definidos en la clase
 * {@see Funciones}, donde se almacenan los mensajes de error.
 *
 * @note Para el correcto funcionamiento del envío del formulario, el middleware
 * CSRF debe estar deshabilitado o gestionado adecuadamente.
 *
 * @package App\Http\Controllers
 */
class AltaCtrl
{
    /**
     * @brief Gestiona el flujo principal del alta de tareas.
     *
     * Este método actúa como punto de entrada del controlador:
     *
     * - **GET**:
     *   - Carga la vista de alta (`alta`) con todos los campos inicializados
     *     a valores vacíos.
     *
     * - **POST**:
     *   - Filtra y valida los datos enviados mediante el formulario.
     *   - Si existen errores de validación, devuelve la vista de alta junto
     *     con los datos introducidos y los mensajes de error.
     *   - Si no existen errores, registra la nueva tarea en la base de datos
     *     y redirige al listado principal.
     *
     * El acceso a este método está restringido exclusivamente a:
     * - Usuarios autenticados.
     * - Usuarios con rol de administrador.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     * Devuelve la vista de alta con datos y errores, o redirige al inicio
     * tras una inserción correcta.
     */
    public function alta()
    {
        // Obtiene la instancia singleton de sesión
        $login = Sesion::getInstance();
        $login->onlyLogged();         // Restringe el acceso a usuarios autenticados
        $login->onlyAdministrador(); // Restringe el acceso a administradores

        if ($_POST) {
            // Inicializa el contenedor de errores
            Funciones::$errores = [];

            // Filtrado y validación de los datos recibidos
            $this->filtraDatos();

            if (!empty(Funciones::$errores)) {
                // Existen errores: se recarga la vista con los datos introducidos
                return view('alta', $_POST);
            } else {
                // No hay errores: se registra la tarea y se redirige
                $model = new Tareas();
                $model->registraAlta($_POST);
                miredirect('/');
            }
        } else {
            // Inicialización de valores por defecto para la vista
            $datos = [
                'nif_cif' => '',
                'persona_contacto' => '',
                'telefono' => '',
                'descripcion' => '',
                'correo' => '',
                'direccion' => '',
                'poblacion' => '',
                'codigo_postal' => '',
                'provincia' => '',
                'estado' => '',
                'operario_encargado' => '',
                'fecha_realizacion' => '',
                'anotaciones' => ''
            ];

            return view('alta', $datos);
        }
    }

    /**
     * @brief Filtra y valida los datos del formulario de alta de tareas.
     *
     * Este método se encarga de realizar todas las validaciones necesarias
     * sobre los datos recibidos mediante el formulario:
     *
     * - Comprobación de campos obligatorios.
     * - Validación del NIF/CIF.
     * - Validación del formato del correo electrónico.
     * - Validación del número de teléfono.
     * - Verificación de la coherencia entre código postal y provincia.
     * - Comprobación de que la fecha de realización sea posterior a la fecha actual.
     *
     * Todos los errores detectados se almacenan en el array estático
     * {@see Funciones::$errores}, utilizando como clave el nombre del campo
     * y como valor el mensaje de error correspondiente.
     *
     * @return void
     */
    private function filtraDatos()
    {
        // Reinicia el array de errores
        Funciones::$errores = [];

        // Recuperación segura de los datos enviados por POST
        $nif_cif = $_POST['nif_cif'] ?? '';
        $persona_contacto = $_POST['persona_contacto'] ?? '';
        $telefono = $_POST['telefono'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $correo = $_POST['correo'] ?? '';
        $codigo_postal = $_POST['codigo_postal'] ?? '';
        $provincia = $_POST['provincia'] ?? '';
        $fecha_realizacion = $_POST['fecha_realizacion'] ?? '';

        // Validación del NIF/CIF
        if ($nif_cif === '') {
            Funciones::$errores['nif_cif'] = "Debe introducir el NIF/CIF de la persona encargada de la tarea";
        } else {
            $resultado = Funciones::validarNif($nif_cif);
            if ($resultado !== true) {
                Funciones::$errores['nif_cif'] = $resultado;
            }
        }

        // Validación de la persona de contacto
        if ($persona_contacto === '') {
            Funciones::$errores['persona_contacto'] = "Debe introducir el nombre de la persona encargada de la tarea";
        }

        // Validación de la descripción
        if ($descripcion === '') {
            Funciones::$errores['descripcion'] = "Debe introducir la descripción de la tarea";
        }

        // Validación del correo electrónico
        if ($correo === '') {
            Funciones::$errores['correo'] = "Debe introducir el correo de la persona encargada de la tarea";
        } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            Funciones::$errores['correo'] = "El correo introducido no es válido";
        }

        // Validación del teléfono
        if ($telefono === '') {
            Funciones::$errores['telefono'] = "Debe introducir el teléfono de la persona encargada de la tarea";
        } else {
            $resultado = Funciones::telefonoValido($telefono);
            if ($resultado !== true) {
                Funciones::$errores['telefono'] = $resultado;
            }
        }

        // Validación del código postal
        if ($codigo_postal === '') {
            Funciones::$errores['codigo_postal'] = "Debe introducir el código postal";
        }

        // Validación de la provincia
        if ($provincia === '') {
            Funciones::$errores['provincia'] = "Debe introducir la provincia";
        }

        // Comprobación de coherencia entre código postal y provincia
        if ($codigo_postal !== '' && preg_match('/^[0-9]{5}$/', $codigo_postal) && $provincia !== '') {
            $resultado = Funciones::validarCodigoPostalProvincia($codigo_postal, $provincia);
            if ($resultado !== true) {
                Funciones::$errores['provincia'] = $resultado;
            }
        }

        // Validación de la fecha de realización
        $fechaActual = date('Y-m-d');
        if ($fecha_realizacion === '') {
            Funciones::$errores['fecha_realizacion'] = "Debe introducir la fecha de realización de la tarea";
        } elseif ($fecha_realizacion <= $fechaActual) {
            Funciones::$errores['fecha_realizacion'] = "La fecha de realización debe ser posterior a la fecha actual";
        }
    }
}
