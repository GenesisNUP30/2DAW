<?php
namespace App\Http\Controllers;

use App\Models\Sesion;
use App\Models\Tareas;

class VerTareasBorradasCtrl
{
    public function index()
    {
        $login = Sesion::getInstance();
        $login->onlyLogged();

        $modelo = new Tareas();
        $tareas = $modelo->listarTareasBorradas();

        return view('listartareasborradas', ['tareas' => $tareas]);
    }
}