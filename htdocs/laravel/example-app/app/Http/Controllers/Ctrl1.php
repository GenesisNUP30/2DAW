<?php

namespace App\Http\Controllers;

use App\Models\Vacio;

class Ctrl1 
{
    public function action1()
    {
        echo "<p>En Action 1</p>";

        echo "<p>Numero del modelo: </p>";
        echo Vacio::unNumero();
    }
}
