<?php

namespace App\Http\Controllers;

use App\Models\Usuarios;
use App\Models\Sesion;
use App\Models\Funciones;

class EditarUsuarioCtrl
{
    public function mostrarFormularioUsuario($id)
    {
        $login = Sesion::getInstance();
        $login->onlyLogged();
        $login->onlyAdministrador();

        $modelo = new Usuarios();
        $usuario = $modelo->obtenerUsuarioPorId($id);

        if (!$usuario) {
            abort(404, 'Usuario no encontrado');
        }

        return view('editarusuario', $usuario);
    }

    public function actualizar($id)
    {
        if ($_POST) {
            $modelo = new Usuarios();
            $usuario = $modelo->obtenerUsuarioPorId($id);

            Funciones::$errores = [];
            $this->filtraDatos($usuario['password']);

            if (!empty(Funciones::$errores)) {
                return view('editarusuario', array_merge($_POST, ['id' => $id]));
            }
            else {
                $modelo->actualizarUsuario($id, $_POST);
                miredirect('/');
            }
        }
    }

    public function filtraDatos($password_actual)
    {
        extract($_POST);

        if ($usuario_nuevo === "") {
            Funciones::$errores['usuario_nuevo'] = "Debe introducir el nombre de usuario";
        }
        
        if ($password_antigua === "") {
            Funciones::$errores['password_antigua'] = "Debe introducir la contraseña antigua";
        } else {
            if ($password_antigua !== $password_actual) {
                Funciones::$errores['password_antigua'] = "La contraseña antigua no coincide con la actual";
            }
        }

        if ($password_nueva === "") {
            Funciones::$errores['password_nuevo'] = "Debe introducir la nueva contraseña";
        }
        else {
            $resultado = Funciones::comprobarPassword($password_antigua, $password_nueva, $password_nueva2);
            if ($resultado !== true) {
                Funciones::$errores['password_nueva'] = $resultado;
            }
        }

        if ($rol_nuevo === "") {
            Funciones::$errores['rol_nuevo'] = "Debe seleccionar el rol";
        }
    }
    
}