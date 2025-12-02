<?php
/**
 * @file EditarUsuarioCtrl.php
 * @brief Controlador encargado de la edición de usuarios en el sistema.
 *
 * Gestiona:
 * - Mostrar el formulario de edición de un usuario.
 * - Validar los datos recibidos.
 * - Actualizar la información del usuario en la base de datos.
 *
 * Se respetan los permisos según rol:
 * - Administrador puede editar cualquier usuario.
 * - Operario solo puede editar su propio usuario y no cambiar el rol.
 */

namespace App\Http\Controllers;

use App\Models\Usuarios;
use App\Models\Sesion;
use App\Models\Funciones;

/**
 * @class EditarUsuarioCtrl
 * @brief Controlador para la edición de usuarios.
 *
 * Se encarga de la visualización del formulario de edición, validación
 * de datos y actualización de los registros de usuarios.
 */
class EditarUsuarioCtrl
{
    /**
     * @brief Muestra el formulario de edición de un usuario.
     *
     * Comprueba permisos según el rol del usuario logueado:
     * - Operario solo puede acceder a su propio usuario.
     * - Administrador puede acceder a cualquier usuario.
     *
     * @param int $id ID del usuario a editar.
     * @return mixed Devuelve la vista 'editarusuario' con los datos del usuario.
     */
    public function mostrarFormularioUsuario($id)
    {
        /** @var Sesion $login Instancia de Sesion para gestionar permisos */
        $login = Sesion::getInstance();
        $login->onlyLogged();

        // Verificación de permisos para operario
        if ($login->getRol() === 'operario' && $_SESSION['id'] != $id) {
            abort(403, 'No tienes permisos para ver este usuario.');
        }

        /** @var Usuarios $modelo Modelo de usuarios */
        $modelo = new Usuarios();
        $usuario = $modelo->obtenerUsuarioPorId($id);

        if (!$usuario) {
            abort(404, 'Usuario no encontrado');
        }

        return view('editarusuario', [
            'id'            => $usuario['id'],
            'usuario_nuevo' => $usuario['usuario'],
            'rol_nuevo'     => $usuario['rol'],
            'rol_logueado'  => $login->getRol()
        ]);
    }

    /**
     * @brief Actualiza los datos de un usuario.
     *
     * Comprueba si se ha enviado un POST y realiza:
     * - Validación de datos.
     * - Actualización del registro en base de datos.
     * - Redirección según rol del usuario logueado.
     *
     * @param int $id ID del usuario a actualizar.
     * @return void Redirige a la página correspondiente tras la actualización.
     */
    public function actualizar($id)
    {
        if ($_POST) {

            $login = Sesion::getInstance();
            $login->onlyLogged();

            // Verificación de permisos para operario
            if ($login->getRol() === 'operario' && $_SESSION['id'] != $id) {
                abort(403, 'No tienes permisos para ver este usuario.');
            }

            $modelo = new Usuarios();
            $usuario = $modelo->obtenerUsuarioPorId($id);

            Funciones::$errores = [];

            // Validación de datos
            $this->filtraDatos(
                $usuario['password'],       // Contraseña actual
                $usuario['usuario'],        // Usuario original
                $login->getRol() === 'administrador' // Permiso para cambiar rol
            );

            // Si hay errores → recargar vista con datos y errores
            if (!empty(Funciones::$errores)) {
                return view('editarusuario', array_merge($_POST, [
                    'id' => $id,
                    'rol_logueado' => $login->getRol()
                ]));
            }

            // Determinar la contraseña final (nueva o actual)
            $passwordFinal = ($_POST['password_nueva'] !== "")
                ? $_POST['password_nueva']
                : $usuario['password'];

            // Determinar rol final
            $rolFinal = ($login->getRol() === 'administrador')
                ? $_POST['rol_nuevo']
                : $usuario['rol'];

            $datosActualizados = [
                'usuario'  => $_POST['usuario_nuevo'],
                'password' => $passwordFinal,
                'rol'      => $rolFinal
            ];

            // Actualizar usuario en la base de datos
            $modelo->actualizarUsuario($id, $datosActualizados);

            // Redirección según rol
            if ($login->getRol() === 'administrador') {
                miredirect('listarusuarios');
            } else {
                miredirect('/');
            }
        }
    }

    /**
     * @brief Valida los datos recibidos desde el formulario de edición.
     *
     * Comprueba:
     * - Usuario no vacío y no repetido.
     * - Contraseña antigua si se quiere cambiar.
     * - Nueva contraseña y confirmación coinciden.
     * - Rol solo si es administrador.
     *
     * @param string $password_actual Contraseña actual del usuario.
     * @param string $usuario_original Nombre de usuario original.
     * @param bool $esAdministrador Si el usuario logueado es administrador.
     * @return void Actualiza Funciones::$errores con los errores encontrados.
     */
    public function filtraDatos($password_actual, $usuario_original, $esAdministrador)
    {
        extract($_POST);

        // Validación de usuario
        if ($usuario_nuevo === "") {
            Funciones::$errores['usuario_nuevo'] = "Debe introducir el nombre de usuario";
        } 
        elseif ($usuario_nuevo !== $usuario_original) {
            $modelo = new Usuarios();
            if ($modelo->usuarioExiste($usuario_nuevo)) {
                Funciones::$errores['usuario_nuevo'] = "El nombre de usuario ya existe";
            }
        }

        // Comprobación si el usuario desea cambiar contraseña
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

        // Validación del rol solo si es administrador
        if ($esAdministrador && $rol_nuevo === "") {
            Funciones::$errores['rol_nuevo'] = "Debe seleccionar un rol";
        }
    }
}
