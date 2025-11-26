<?php
namespace App\Http\Controllers;

use App\Models\Login;
use Illuminate\Support\Facades\Log;

class LoginCtrl
{
    public function login()
    {
        $model = new Login();
        $usuario = $_POST['usuario'];
        $password = $_POST['password'];

        if ($model->validarLogin($usuario, $password)) {
            // Login exitoso
            return view('index');
        } else {
            // Login fallido
            return view('login', ['error' => 'Credenciales inválidas.']);
        }
    }
}