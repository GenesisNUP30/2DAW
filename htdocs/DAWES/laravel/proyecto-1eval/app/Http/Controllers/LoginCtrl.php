<?php
namespace App\Http\Controllers;

use App\Models\Sesion;


class LoginCtrl
{
    public function login()
    {
        $model = Sesion::getInstance();
        
        if ($_POST) {
            $usuario = $_POST['usuario'];
            $password = $_POST['password'];

            if ($model->validarLogin($usuario, $password)) {
                // Login exitoso
                miredirect('/');
            } else {
                // Login fallido
                return view('login', ['error' => 'Credenciales inválidas.']);
            }
        }
        return view('login');
    }

    public function logout()
    {
        $model = Sesion::getInstance();
        $model->logout();
        miredirect('login');
    }

}