<?php
namespace App\Http\Controllers;

use App\Models\Sesion;
use App\Models\Tareas;

class EliminarCtrl
{
    public function confirmar($id)
    {
        $login = Sesion::getInstance();
        $login->onlyLogged();
        $login->onlyAdministrador();

        $modelo = new Tareas();
        $tarea = $modelo->obtenerTareaPorId($id);

        if (!$tarea) {
            abort(404, 'Tarea no encontrada');
        }

        return view('eliminar', ['tarea' => $tarea]);
    }

    public function eliminar($id)
    {
        $modelo = new Tareas();
        $modelo->eliminarTarea($id);
        miredirect('/');
    }
}
