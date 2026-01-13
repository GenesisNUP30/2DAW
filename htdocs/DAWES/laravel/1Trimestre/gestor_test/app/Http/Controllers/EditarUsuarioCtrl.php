<?php
/**
 * @file EditarUsuarioCtrl.php
 *
 * @brief Controlador encargado de la edición de usuarios del sistema.
 *
 * Este controlador gestiona el ciclo completo de modificación de usuarios:
 * - Visualización del formulario de edición.
 * - Validación de los datos introducidos.
 * - Actualización de la información del usuario en la base de datos.
 *
 * El acceso y las acciones permitidas dependen del rol del usuario autenticado:
 * - **Administrador**: puede editar cualquier usuario y modificar su rol.
 * - **Operario**: solo puede editar su propio usuario y no puede cambiar el rol.
 */

namespace App\Http\Controllers;

use App\Models\Usuarios;
use App\Models\Sesion;
use App\Models\Funciones;

/**
 * @class EditarUsuarioCtrl
 *
 * @brief Controlador responsable de la edición de usuarios.
 *
 * Centraliza la lógica necesaria para:
 * - Controlar permisos de acceso según el rol del usuario.
 * - Mostrar el formulario de edición con los datos actuales.
 * - Validar los datos enviados por el formulario.
 * - Persistir los cambios en la base de datos.
 *
 * La gestión de errores se realiza mediante {@see Funciones::$errores}.
 *
 * @package App\Http\Controllers
 */
class EditarUsuarioCtrl
{
    /**
     * @brief Muestra el formulario de edición de un usuario.
     *
     * Este método:
     * - Comprueba que el usuario esté autenticado.
     * - Verifica los permisos según el rol:
     *   - Un operario solo puede acceder a su propio usuario.
     *   - Un administrador puede acceder a cualquier usuario.
     * - Recupera los datos del usuario a editar.
     * - Envía la información necesaria a la vista `editarusuario`.
     *
     * Si el usuario no existe o no se tienen permisos suficientes,
     * se devuelve el código de error HTTP correspondiente.
     *
     * @param int $id Identificador único del usuario a editar.
     *
     * @return \Illuminate\View\View
     * Devuelve la vista `editarusuario` con los datos del usuario.
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * - 403 si no se tienen permisos.
     * - 404 si el usuario no existe.
     */
    public function mostrarFormularioUsuario($id)
    {
        /** @var Sesion $login Instancia de sesión para control de autenticación */
        $login = Sesion::getInstance();
        $login->onlyLogged();

        // Control de permisos para operarios
        if ($login->getRol() === 'operario' && $_SESSION['id'] != $id) {
            abort(403, 'No tienes permisos para ver este usuario.');
        }

        /** @var Usuarios $modelo Modelo de acceso a usuarios */
        $modelo = new Usuarios();
        $usuario = $modelo->obtenerUsuarioPorId($id);

        // Comprobación de existencia del usuario
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
     * @brief Procesa la actualización de los datos de un usuario.
     *
     * Este método se ejecuta tras el envío del formulario de edición:
     *
     * - Verifica que el usuario esté autenticado.
     * - Comprueba los permisos según el rol.
     * - Valida los datos enviados por el formulario.
     * - Determina si se debe actualizar la contraseña.
     * - Actualiza el rol solo si el usuario logueado es administrador.
     * - Persiste los cambios en la base de datos.
     *
     * Tras la actualización, redirige al usuario a la página correspondiente
     * según su rol.
     *
     * @param int $id Identificador único del usuario a actualizar.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     * Recarga la vista con errores o redirige tras una actualización correcta.
     */
    public function actualizar($id)
    {
        if ($_POST) {

            $login = Sesion::getInstance();
            $login->onlyLogged();

            // Control de permisos para operarios
            if ($login->getRol() === 'operario' && $_SESSION['id'] != $id) {
                abort(403, 'No tienes permisos para ver este usuario.');
            }

            $modelo = new Usuarios();
            $usuario = $modelo->obtenerUsuarioPorId($id);

            // Inicialización del contenedor de errores
            Funciones::$errores = [];

            // Validación de los datos del formulario
            $this->filtraDatos(
                $usuario['password'],              // Contraseña actual
                $usuario['usuario'],               // Nombre de usuario original
                $login->getRol() === 'administrador' // Permiso para cambiar rol
            );

            // Si existen errores, se recarga la vista
            if (!empty(Funciones::$errores)) {
                return view('editarusuario', array_merge($_POST, [
                    'id' => $id,
                    'rol_logueado' => $login->getRol()
                ]));
            }

            // Determinación de la contraseña final
            $passwordFinal = ($_POST['password_nueva'] !== "")
                ? $_POST['password_nueva']
                : $usuario['password'];

            // Determinación del rol final
            $rolFinal = ($login->getRol() === 'administrador')
                ? $_POST['rol_nuevo']
                : $usuario['rol'];

            // Preparación de los datos a actualizar
            $datosActualizados = [
                'usuario'  => $_POST['usuario_nuevo'],
                'password' => $passwordFinal,
                'rol'      => $rolFinal
            ];

            // Actualización del usuario en la base de datos
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
     * @brief Filtra y valida los datos del formulario de edición de usuarios.
     *
     * Este método comprueba:
     * - Que el nombre de usuario no esté vacío.
     * - Que el nombre de usuario no esté repetido (si se modifica).
     * - Si se desea cambiar la contraseña:
     *   - Que la contraseña antigua sea correcta.
     *   - Que la nueva contraseña cumpla los requisitos y coincida con la confirmación.
     * - Que el rol esté informado únicamente si el usuario logueado es administrador.
     *
     * Los errores detectados se almacenan en {@see Funciones::$errores}.
     *
     * @param string $password_actual Contraseña actual almacenada del usuario.
     * @param string $usuario_original Nombre de usuario original antes de la edición.
     * @param bool $esAdministrador Indica si el usuario logueado es administrador.
     *
     * @return void
     */
    public function filtraDatos($password_actual, $usuario_original, $esAdministrador)
    {
        extract($_POST);

        // Validación del nombre de usuario
        if ($usuario_nuevo === "") {
            Funciones::$errores['usuario_nuevo'] = "Debe introducir el nombre de usuario";
        } elseif ($usuario_nuevo !== $usuario_original) {
            $modelo = new Usuarios();
            if ($modelo->usuarioExiste($usuario_nuevo)) {
                Funciones::$errores['usuario_nuevo'] = "El nombre de usuario ya existe";
            }
        }

        // Comprobación de cambio de contraseña
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

            $resultado = Funciones::comprobarPassword($password_antigua,$password_nueva, $password_nueva2);

            if ($resultado !== true) {
                Funciones::$errores['password_nueva'] = $resultado;
            }
        }

        // Validación del rol únicamente para administradores
        if ($esAdministrador && $rol_nuevo === "") {
            Funciones::$errores['rol_nuevo'] = "Debe seleccionar un rol";
        }
    }
}
