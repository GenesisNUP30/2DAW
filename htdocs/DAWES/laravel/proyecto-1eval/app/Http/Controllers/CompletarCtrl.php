<?php

namespace App\Http\Controllers;

use App\Models\Funciones;
use App\Models\Sesion;
use App\Models\Tareas;

class CompletarCtrl
{
    public function mostrarFormulario($id)
    {
        $login = Sesion::getInstance();
        $login->onlyLogged();
        $login->onlyOperario();

        $modelo = new Tareas();
        $tarea = $modelo->obtenerTareaPorId($id);

        if (!$tarea) {
            abort(404, 'Tarea no encontrada');
        }

        return view('completar', $tarea);
    }

    public function completar($id)
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
                return view('completar', array_merge($_POST, ['id' => $id]));
            }

            $datos = [
                'estado' => $_POST['estado'],
                'anotaciones_posteriores' => $_POST['anotaciones_posteriores'],
            ];
            $modelo->completarTarea($id, $datos);
            miredirect('/');
        }
    }

    private function filtraDatos()
    {
        extract($_POST);

        if ($estado === "") {
            Funciones::$errores['estado'] = "Debe seleccionar el estado de la tarea";
        }

        if ($anotaciones_posteriores === "") {
            Funciones::$errores['anotaciones_posteriores'] = "Debe introducir las anotaciones posteriores";
        }
    }
}
