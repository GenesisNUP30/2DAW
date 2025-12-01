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

        if ($login->getRol() === 'operario' && $_SESSION['id'] != $id) {
            abort(403, 'No tienes permisos para ver este usuario.');
        }

        $modelo = new Usuarios();
        $usuario = $modelo->obtenerUsuarioPorId($id);

        if (!$usuario) {
            abort(404, 'Usuario no encontrado');
        }


        return view('editarusuario', [
            'id' => $usuario['id'],
            'usuario_nuevo' => $usuario['usuario'],
            'rol_nuevo' => $usuario['rol'],
            'rol_logueado' => $login->getRol()
        ]);
    }

    public function actualizar($id)
    {
        if ($_POST) {

            $login = Sesion::getInstance();
            $login->onlyLogged();


            if ($login->getRol() === 'operario' && $_SESSION['id'] != $id) {
                abort(403, 'No tienes permisos para ver este usuario.');
            }

            $modelo = new Usuarios();
            $usuario = $modelo->obtenerUsuarioPorId($id);

            Funciones::$errores = [];

            $login = Sesion::getInstance();
            $login->onlyLogged();

            $this->filtraDatos(
                $usuario['password'],
                $usuario['usuario'],
                $login->getRol() === 'administrador'
            );

            if (!empty(Funciones::$errores)) {
                return view('editarusuario', array_merge($_POST, [
                    'id' => $id,
                    'rol_logueado' => $login->getRol()
                ]));
            }

            // Si NO cambia la contraseña, se mantiene la antigua
            $passwordFinal = ($_POST['password_nueva'] !== "")
                ? $_POST['password_nueva']
                : $usuario['password'];

            // Si el operario no puede cambiar rol, usamos el suyo actual
            $rolFinal = ($login->getRol() === 'administrador')
                ? $_POST['rol_nuevo']
                : $usuario['rol'];

            $datosActualizados = [
                'usuario'  => $_POST['usuario_nuevo'],
                'password' => $passwordFinal,
                'rol'      => $rolFinal
            ];

            $modelo->actualizarUsuario($id, $datosActualizados);
            if ($login->getRol() === 'administrador') {
                miredirect('listarusuarios');
            } else {
                // El operario solo puede editar su usuario, así que vuelve a inicio
                miredirect('/');
            }
        }
    }

    public function filtraDatos($password_actual, $usuario_original, $esAdministrador)
    {
        extract($_POST);

        if ($usuario_nuevo === "") {
            Funciones::$errores['usuario_nuevo'] = "Debe introducir el nombre de usuario";
        } else if ($usuario_nuevo !== $usuario_original) {
            $modelo = new Usuarios();
            if ($modelo->usuarioExiste($usuario_nuevo)) {
                Funciones::$errores['usuario_nuevo'] = "El nombre de usuario ya existe";
            }
        }

        $quiereCambiarPassword = ($password_nueva !== "" || $password_nueva2 !== "");

        if ($quiereCambiarPassword) {

            if ($password_antigua === "") {
                Funciones::$errores['password_antigua'] = "Debe introducir la contraseña antigua";
                return;
            }

            if ($password_antigua !== $password_actual) {
                Funciones::$errores['password_antigua'] = "La contraseña actual no es correcta";
                return;
            }

            $resultado = Funciones::comprobarPassword($password_antigua, $password_nueva, $password_nueva2);
            if ($resultado !== true) {
                Funciones::$errores['password_nueva'] = $resultado;
            }
        }

        // --- Validación rol ---
        if ($esAdministrador) {
            if ($rol_nuevo === "") {
                Funciones::$errores['rol_nuevo'] = "Debe seleccionar un rol";
            }
        }
    }
}
