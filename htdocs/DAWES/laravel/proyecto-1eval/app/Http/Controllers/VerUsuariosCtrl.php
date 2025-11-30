<?php
namespace App\Http\Controllers;

use App\Models\Usuarios;
use App\Models\Sesion;


class VerUsuariosCtrl
{
    public function index()
    {
        $login = Sesion::getInstance();
        $login->onlyLogged();
        $login->onlyAdministrador();

        $modelo = new Usuarios();
        $usuarios = $modelo->listarUsuarios();
        return view('listarusuarios', ['usuarios' => $usuarios]);
    }
 
}