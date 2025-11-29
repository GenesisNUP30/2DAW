<?php
namespace App\Http\Controllers;

use App\Models\Usuarios;
use App\Models\Sesion;

class EliminarUsuarioCtrl
{
    public function confirmar($id)
    {
        $login = Sesion::getInstance();
        $login->onlyLogged();
        $login->onlyAdministrador();
        
        $modelo = new Usuarios();
        $usuario = $modelo->obtenerUsuarioPorId($id);

        if (!$usuario) {
            abort(404, 'Usuario no encontrado');
        }
        
        return view('eliminarusuario', ['usuario' => $usuario]);
    }

    public function eliminar($id)
    {
        $modelo = new Usuarios();
        $modelo->eliminarUsuario($id);
        miredirect('/');
    }
}