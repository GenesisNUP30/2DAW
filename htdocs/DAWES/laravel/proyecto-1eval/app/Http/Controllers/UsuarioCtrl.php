<?php
namespace App\Http\Controllers;

use App\Models\Usuarios;
use App\Models\Funciones;

class UsuarioCtrl
{
    public function index()
    {
        $modelo = new Usuarios();
        $usuarios = $modelo->listarUsuarios();
        return view('usuarios', ['usuarios' => $usuarios]);
    }
    
}