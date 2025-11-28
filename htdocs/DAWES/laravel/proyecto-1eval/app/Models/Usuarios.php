<?php

namespace App\Models;

use App\Models\DB;

class Usuarios
{
    private $bd;

    public function __construct()
    {
        $this->bd = DB::getInstance();
    }

    
}