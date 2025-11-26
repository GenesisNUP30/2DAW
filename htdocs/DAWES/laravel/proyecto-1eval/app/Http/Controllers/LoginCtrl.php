<?php
namespace App\Http\Controllers;

use App\Models\Login;


class LoginCtrl
{
    public function login()
    {
        $model = Login::getInstance();
        
        if ($_POST) {
            $usuario = $_POST['usuario'];
            $password = $_POST['password'];

            if ($model->validarLogin($usuario, $password)) {
                // Login exitoso
                header('Location: /DAWES/laravel/proyecto-1eval/public/');
                exit();
            } else {
                // Login fallido
                return view('login', ['error' => 'Credenciales inválidas.']);
            }
        }
        return view('login');
    }

    public function logout()
    {
        $model = Login::getInstance();
        $model->logout();
        header('Location: /DAWES/laravel/proyecto-1eval/public/login');
        exit();
    }

}