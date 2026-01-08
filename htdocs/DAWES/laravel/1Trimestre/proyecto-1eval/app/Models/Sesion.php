<?php

namespace App\Models;

use App\Models\DB;
use App\Models\ConfigAvanzada;

/**
 * @class Sesion
 * @brief Gestión de sesiones de usuario con patrón Singleton.
 *
 * Esta clase gestiona el login, logout, permisos de acceso, roles de usuario,
 * inicio de sesión desde cookies y almacenamiento de datos de sesión.
 *
 * Implementa:
 * - Validación de login con usuario y contraseña.
 * - Creación y verificación de token "recordarme".
 * - Control de acceso según rol (operario/administrador).
 * - Patrón Singleton para asegurar una única instancia.
 *
 * @package App\Models
 */
class Sesion
{
    /**
     * Instancia única de la clase (patrón Singleton).
     *
     * @var Sesion|null
     */
    private static $instance = null;

    /**
     * Constructor privado.
     *
     * Inicia la sesión e impide que la clase pueda ser instanciada
     * desde fuera para mantener el patrón Singleton.
     * Verifica si se puede iniciar sesión desde cookie "recordarme".
     */
    public function __construct()
    {
        session_start();
        // Crear instancia de ConfigAvanzada
        $config = ConfigAvanzada::getInstance();

        // Obtener tiempo de sesión 
        $tiempoSesion = (int) $config->get('tiempo_sesion', 600);

        //Controlar el tiempo de inactividad
        if (isset($_SESSION['ultima_actividad'])) {
            // Si la última actividad es mayor que el tiempo de sesión, se cierra la sesión
            if (time() - $_SESSION['ultima_actividad'] > $tiempoSesion) {
                $this->logout();
            }
        }

        // Guardar la hora de la última actividad
        $_SESSION['ultima_actividad'] = time();

        if (!$this->isLogged() && isset($_COOKIE['recordar_usuario'])) {
            $this->loginDesdeCookie();
        }
    }

    /**
     * Evita la clonación del objeto Singleton.
     *
     * @throws \Exception Siempre, para evitar duplicación
     */
    public function __clone()
    {
        throw new \Exception("No se permite la clonación de esta clase.");
    }

    /**
     * Obtiene la instancia única de la clase Sesion.
     *
     * Si la instancia aún no existe, la crea. Si ya existe, la devuelve.
     *
     * @return Sesion Instancia única del gestor de sesiones
     */
    public static function getInstance(): Sesion
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Valida las credenciales del usuario y crea la sesión.
     *
     * Consulta la base de datos buscando un usuario que coincida con
     * el nombre y contraseña introducidos. Si existe, crea la sesión
     * asignando ID, usuario, rol y hora de login.
     * Además, crea una cookie para recordar el último usuario.
     * Si se envía el formulario con el checkbox "recordarme",
     * se crea un token de sesión válido por 3 días.
     *
     * @param string $usuario Nombre del usuario
     * @param string $password Contraseña del usuario
     * @return bool True si las credenciales son correctas, false en caso contrario
     */
    public function validarLogin(string $usuario, string $password): bool
    {
        date_default_timezone_set('Europe/Madrid');

        $db = DB::getInstance();

        // Verifica si el usuario existe en la base de datos
        $user = $db->LeeUnRegistro('usuarios', 'usuario = "' . $usuario . '" AND password = "' . $password . '"');

        // Si existe, crea la sesión
        if ($user) {
            $_SESSION['id'] = $user['id']; // ID del usuario
            $_SESSION['usuario'] = $usuario; // Nombre del usuario
            $_SESSION['rol'] = $user['rol']; // Rol del usuario
            $_SESSION['logado'] = true; // Marca logueado como true
            $_SESSION['hora_logado'] = date('Y-m-d H:i:s'); // Hora de login

            // Guarda el nombre del último usuario en la cookie durante 3 días (para que coincida con "recordarme")
            setcookie(
                'ultimo_usuario',
                $usuario,
                time() + (3 * 24 * 60 * 60),
                '/');

            // Si se ha seleccionado el checkbox "recordarme", crea el token de sesión
            if (isset($_POST['recordarme'])) {
                $this->crearTokenRecordarme($usuario);
            }

            return true;
        }
        return false;
    }

    /**
     * Si el usuario ha seleccionado el checkbox "recordarme",
     * crea un token de sesión válido por 3 días.
     * Genera un selector y un validador aleatorios y los encripta. 
     * Invalida los tokens antiguos, almacena el nuevo en la BD y crea las cookies correspondientes.
     * @param string $usuario Nombre del usuario
     * @return void
     * 
     */

    public function crearTokenRecordarme(string $usuario): void
    {
        $db = DB::getInstance();

        // Generar selector y validator aleatorios
        $selector = bin2hex(random_bytes(8));
        $validator = bin2hex(random_bytes(32));

        // Encriptar selector y validator
        $selectorHash = password_hash($selector, PASSWORD_DEFAULT);
        $validatorHash = password_hash($validator, PASSWORD_DEFAULT);

        // Asignar fecha de expiración a 3 días
        $fecha_expira = date('Y-m-d H:i:s', time() + (3 * 24 * 60 * 60));

        // Invalidar tokens antiguos
        $db->query("UPDATE login_token SET is_expired = 1 WHERE usuario = '$usuario'");

        //Guardar nuevo token
        $db->query("INSERT INTO login_token (usuario, selector_hash, validator_hash, expiry_date)
        VALUES ('$usuario', '$selectorHash', '$validatorHash', '$fecha_expira')
        ");

        // Guardar cookie durante 3 días
        setcookie('recordar_usuario', $usuario, time() + (3 * 24 * 60 * 60), '/');
        setcookie('recordar_selector', $selector, time() + (3 * 24 * 60 * 60), '/');
        setcookie('recordar_validator', $validator, time() + (3 * 24 * 60 * 60), '/');
    }

    /**
     * Intenta iniciar sesión automáticamente usando las cookies "recordarme".
     * Verifica el token almacenado en la base de datos.
     * Si es válido, crea la sesión del usuario.
     * Si no es válido, invalida el token y borra las cookies.
     * @return void
     */

    public function loginDesdeCookie(): void
    {
        $db = DB::getInstance();
        $fecha_actual = date('Y-m-d H:i:s');

        // Obtiene los valores de las cookies y las asigna a variables
        $usuario = $_COOKIE['recordar_usuario'];
        $selector = $_COOKIE['recordar_selector'];
        $validator = $_COOKIE['recordar_validator'];

        // Busca el token del usuario en la base de datos
        $token = $db->LeeUnRegistro(
            'login_token',
            "usuario = '$usuario' AND is_expired = 0 AND expiry_date > '$fecha_actual'"
        );

        // Verifica si el token es válido
        // Si es válido, crea la sesión del usuario
        if ($token && password_verify($selector, $token['selector_hash']) && password_verify($validator, $token['validator_hash'])) {
            // Recuperar datos completos del usuario
            $user = $db->LeeUnRegistro('usuarios', 'usuario = "' . $usuario . '"');
            if ($user) {
                $_SESSION['usuario'] = $usuario;
                $_SESSION['rol'] = $user['rol']; // <- esto faltaba
                $_SESSION['logado'] = true;
                $_SESSION['hora_logado'] = date('Y-m-d H:i:s'); // opcional
            } else {
                // Usuario no encontrado → borrar cookies
                $this->borrarCookiesRecordarme();
            }
        }
    }

    /**
     * Borra las cookies relacionadas con la función "recordarme".
     * @return void
     */
    public function borrarCookiesRecordarme(): void
    {
        setcookie('recordar_usuario', '', time() - 3600, '/');
        setcookie('recordar_selector', '', time() - 3600, '/');
        setcookie('recordar_validator', '', time() - 3600, '/');
    }


    /**
     * Cierra completamente la sesión del usuario.
     *
     * Elimina las variables de sesión y destruye la sesión.
     *
     * @return void
     */
    public function logout(): void
    {
        $_SESSION = [];
        session_destroy();
        $this->borrarCookiesRecordarme();
    }

    /**
     * Comprueba si el usuario está actualmente logueado.
     *
     * @return bool True si el usuario inició sesión, false en caso contrario
     */
    public function isLogged(): bool
    {
        return !empty($_SESSION['logado']);
    }

    /**
     * Restringe acceso a funciones solo para usuarios autenticados.
     *
     * Si no hay usuario logueado, se redirige al formulario de login.
     *
     * @return void
     */
    public function onlyLogged(): void
    {
        if (!$this->isLogged()) {
            miredirect('login');
        }
    }

    /**
     * Restringe acceso a usuarios con rol "operario".
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     *         Si el usuario no tiene permiso → error 403
     *
     * @return void
     */
    public function onlyOperario(): void
    {
        if ($this->getRol() !== 'operario') {
            abort(403, 'No tienes permisos para acceder a esta funcionalidad.');
        }
    }

    /**
     * Restringe acceso a usuarios con rol "administrador".
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     *         Si el usuario no tiene permiso → error 403
     *
     * @return void
     */
    public function onlyAdministrador(): void
    {
        if ($this->getRol() !== 'administrador') {
            abort(403, 'No tienes permisos para acceder a esta funcionalidad.');
        }
    }

    /**
     * Obtiene el nombre del usuario autenticado.
     *
     * @return string Nombre del usuario
     */
    public function getUsuario(): string
    {
        return $_SESSION['usuario'];
    }

    /**
     * Obtiene el rol del usuario autenticado.
     *
     * @return string Rol del usuario (administrador / operario)
     */
    public function getRol(): string
    {
        return $_SESSION['rol'];
    }

    /**
     * Obtiene la fecha y hora exacta en la que el usuario inició sesión.
     *
     * @return string Fecha y hora del inicio de sesión (Y-m-d H:i:s)
     */
    public function getHoraLogeada(): string
    {
        return $_SESSION['hora_logado'];
    }
}
