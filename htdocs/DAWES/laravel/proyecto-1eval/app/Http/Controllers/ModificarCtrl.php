<?php

namespace App\Http\Controllers;

use App\Models\Funciones;
use App\Models\Sesion;
use App\Models\Tareas;
use Session;

class ModificarCtrl
{
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
                return view('modificar', array_merge($_POST, ['id' => $id]));
            } else {
                $modelo->actualizarTarea($id, $_POST);
                miredirect('/');
            }
        }
    }

    private function filtraDatos()
    {
        extract($_POST);

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
        } else {
            if ($fecha_realizacion <= $fechaActual) {
                Funciones::$errores['fecha_realizacion'] = "La fecha de realización debe ser posterior a la fecha actual";
            }
        }
    }
}

