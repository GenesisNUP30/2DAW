<?php

namespace App\Http\Controllers;

use App\Models\Funciones;

use App\Models\Tareas;

class CompletarCtrl
{
    public function mostrarFormulario($id)
    {
        //AL HACER $_SESSION ESTOY USANDO LA FUNCION DE LARAVEL Y ME DICE QUE $_SESSION
        //NO EXISTE, 
        // COMO LO HAGO, CON ESTO: 
        //$model = Login::getInstance(); $rol = $model->getRol();
        
        if ($_SESSION['rol'] != 'operario') {
            abort(404, 'No tiene permiso para completar esta tarea');
        }

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
            header('Location: /DAWES/laravel/proyecto-1eval/public/');
            exit;
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
