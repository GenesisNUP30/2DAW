<?php
namespace App\Http\Controllers;

use App\Models\Sesion;
use App\Models\Tareas;
use Illuminate\Support\Facades\Log;

class InicioCtrl {
    public function index()
    {
        $login = Sesion::getInstance();
        $login->onlyLogged();

        $modelo = new Tareas();
        $tareas = $modelo->listarTareas();
        Log::debug("InciioCtrl::index\n".print_r($tareas, true));
        
        return view('index', ['tareas' => $tareas]);
    }

    public function verTarea($id)
    {
        $login = Sesion::getInstance();
        $login->onlyLogged();
        $login->onlyAdministrador(); 

        $modelo = new Tareas();
        $tarea = $modelo->obtenerTareaPorId($id);

        if (!$tarea) {
            abort(404, "Tarea no encontrada");
        }

        return view('tareadetalle', ['tarea' => $tarea]);
    }
}