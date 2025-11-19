<?php

namespace App\Http\Controllers;

use App\Models\Funciones;

class AltaCtrl 
{
    public function alta() 
    {
        if ($_POST) {
            // Tenemos que filtrar
            $this->filtraDatos();
            if (!empty(Funciones::$errores)) {
                return view('alta', $_POST);
            }
        }
    }
}
