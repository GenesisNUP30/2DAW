<?php

namespace App\Http\Controllers;

use App\Models\Funciones;

use App\Models\Tareas;

class ModificarCtrl
{
    public function mostrarFormulario($id)
    {
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
                return redirect('/')->with('mensaje', 'Tarea actualizada correctamente.');
            }
        }
    }

    private function filtraDatos()
    {
        extract($_POST);

        if ($nifCif == "") {
            Funciones::$errores['nif_cif'] = "Debe introducir el NIF/CIF de la persona encargada de la tarea";
        } else {
            $resultado = Funciones::validarNif($nifCif);
            if ($resultado !== true) {
                Funciones::$errores['nifCif'] = $resultado;
            }
        }

        if ($personaNombre === "") {
            Funciones::$errores['nombre_persona'] = "Debe introducir el nombre de la persona encargada de la tarea";
        }

        if ($descripcion === "") {
            Funciones::$errores['descripcion_'] = "Debe introducir la descripción de la tarea";
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

        if ($codigoPostal != "" && !preg_match("/^[0-9]{5}$/", $codigoPostal)) {
            Funciones::$errores['codigo_postal'] = "El código postal introducido no es válido, debe tener 5 números";
        }

        if ($provincia === "") {
            Funciones::$errores['provincia'] = "Debe introducir la provincia";
        }

        $fechaActual = date('Y-m-d');
        if ($fechaRealizacion == "") {
            Funciones::$errores['fecha_realizacion'] = "Debe introducir la fecha de realización de la tarea";
        } else {
            if ($fechaRealizacion <= $fechaActual) {
                Funciones::$errores['fecha_realizacion'] = "La fecha de realización debe ser posterior a la fecha actual";
            }
        }
    }
}

