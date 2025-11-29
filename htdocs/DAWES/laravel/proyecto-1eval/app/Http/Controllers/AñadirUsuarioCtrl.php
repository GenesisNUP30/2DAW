<?php
namespace App\Http\Controllers;

use App\Models\Usuarios;
use App\Models\Sesion;
use App\Models\Funciones;

class AñadirUsuarioCtrl
{
    public function añadirUsuario()
    {
        $login = Sesion::getInstance();
        $login->onlyLogged();
        $login->onlyAdministrador();

        if ($_POST) {
            Funciones::$errores = [];
            //Filtramos los datos
            $this->filtraDatos();
            if (!empty(Funciones::$errores)) {
                return view('añadirusuario', $_POST);
            }
            else {
                $modelo = new Usuarios();
                $modelo->registraUsuario($_POST);
                miredirect('/');
            }
        }
        else {
            $datos = [
                'usuario' => '',
                'password' => '',
                'rol' => '',
            ];
            return view('añadirusuario', $datos);
        }
    }

    public function filtraDatos()
    {
        extract($_POST);

        if ($usuario === "") {
            Funciones::$errores['usuario'] = "Debe introducir el nombre de usuario";
        }

        if ($password === "") {
            Funciones::$errores['password'] = "Debe introducir la contraseña";
        }

        if ($rol === "") {
            Funciones::$errores['rol'] = "Debe seleccionar el rol";
        }
    }
}