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
            } else {
                $modelo = new Usuarios();
                $modelo->registraUsuario($_POST);
                miredirect('listarusuarios');
            }
        } else {
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

        // USUARIO
        if ($usuario === "") {
            Funciones::$errores['usuario'] = "Debe introducir el nombre de usuario";
        }

        // PASSWORD
        if ($password === "") {
            Funciones::$errores['password'] = "Debe introducir la contraseña";
        }

        // CONFIRMAR PASSWORD
        if ($password2 === "") {
            Funciones::$errores['password2'] = "Debe confirmar la contraseña";
        } elseif ($password !== $password2) {
            Funciones::$errores['password2'] = "Las contraseñas no coinciden";
        }

        // ROL
        if ($rol === "") {
            Funciones::$errores['rol'] = "Debe seleccionar el rol";
        }
    }
}
