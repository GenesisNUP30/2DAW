<?php
namespace App\Http\Controllers;

use App\Models\Tareas;

class InicioCtrl {
    public function index()
    {
        $modelo = new Tareas();
        $tareas = $modelo->listarTareas();
        return view('index', ['tareas' => $tareas]);
    }
}