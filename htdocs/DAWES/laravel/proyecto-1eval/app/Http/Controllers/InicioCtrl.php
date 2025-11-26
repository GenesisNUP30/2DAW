<?php
namespace App\Http\Controllers;

use App\Models\Login;
use App\Models\Tareas;
use Illuminate\Support\Facades\Log;

class InicioCtrl {
    public function index()
    {
        $login = Login::getInstance();
        $login->onlyLogged();

        $modelo = new Tareas();
        $tareas = $modelo->listarTareas();
        Log::debug("InciioCtrl::index\n".print_r($tareas, true));
        
        return view('index', ['tareas' => $tareas]);
    }
}