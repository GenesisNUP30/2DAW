<?php
/**
 * @file Usuarios.php
 * @brief Modelo para la gestión de usuarios en el sistema.
 *
 * Proporciona métodos para:
 * - Listar usuarios.
 * - Registrar nuevos usuarios.
 * - Obtener información de un usuario por ID.
 * - Comprobar existencia de un usuario.
 * - Actualizar usuarios existentes.
 * - Eliminar usuarios.
 */

namespace App\Models;

use App\Models\DB;

/**
 * @class Usuarios
 * @brief Modelo que maneja la tabla 'usuarios' de la base de datos.
 *
 * Este modelo encapsula todas las operaciones CRUD sobre usuarios,
 * utilizando la clase DB para la interacción con la base de datos.
 */
class Usuarios
{
    /**
     * @var DB Instancia de la clase de conexión a la base de datos.
     */
    private $bd;

    /**
     * @brief Constructor que inicializa la conexión a la base de datos.
     */
    public function __construct()
    {
        $this->bd = DB::getInstance();
    }

    /**
     * @brief Obtiene todos los usuarios registrados.
     *
     * @return array Arreglo asociativo con los datos de todos los usuarios.
     */
    public function listarUsuarios()
    {
        $sql = "SELECT * FROM usuarios";
        $resultado = $this->bd->query($sql);

        $usuarios = [];
        while ($fila = $this->bd->LeeRegistro($resultado)) {
            $usuarios[] = $fila;
        }
        return $usuarios;
    }

    /**
     * @brief Registra un nuevo usuario en la base de datos.
     *
     * @param array $datos Arreglo con los datos del usuario ['usuario', 'password', 'rol'].
     */
    public function registraUsuario(array $datos)
    {
        $sql = "INSERT INTO usuarios (
            usuario,
            password,
            rol
        ) VALUES (
            '{$this->bd->escape($datos['usuario'])}',
            '{$this->bd->escape($datos['password'])}',
            '{$this->bd->escape($datos['rol'])}'
        )";

        $this->bd->query($sql);
    }

    /**
     * @brief Obtiene los datos de un usuario por su ID.
     *
     * @param int $id ID del usuario.
     * @return array|null Arreglo asociativo con los datos del usuario o null si no existe.
     */
    public function obtenerUsuarioPorId($id)
    {
        $sql = "SELECT * FROM usuarios WHERE id = '{$this->bd->escape($id)}'";
        $resultado = $this->bd->query($sql);
        return $this->bd->LeeRegistro($resultado);
    }

    /**
     * @brief Comprueba si un usuario ya existe en la base de datos.
     *
     * @param string $usuario Nombre de usuario a comprobar.
     * @return bool true si el usuario existe, false si no.
     */
    public function usuarioExiste($usuario)
    {
        $usuario = $this->bd->escape($usuario);
        $sql = "SELECT id FROM usuarios WHERE usuario = '$usuario'";
        $resultado = $this->bd->query($sql);
        return $this->bd->LeeRegistro($resultado) !== null;
    }

    /**
     * @brief Actualiza los datos de un usuario existente.
     *
     * @param int $id ID del usuario a actualizar.
     * @param array $datos Arreglo con los nuevos datos ['usuario', 'password', 'rol'].
     */
    public function actualizarUsuario($id, array $datos)
    {
        $sql = "UPDATE usuarios SET
            usuario = '{$this->bd->escape($datos['usuario'])}',
            password = '{$this->bd->escape($datos['password'])}',
            rol = '{$this->bd->escape($datos['rol'])}'
            WHERE id = '{$this->bd->escape($id)}'";

        $this->bd->query($sql);
    }

    /**
     * @brief Elimina un usuario de la base de datos.
     *
     * @param int $id ID del usuario a eliminar.
     */
    public function eliminarUsuario($id)
    {
        $sql = "DELETE FROM usuarios WHERE id = '{$this->bd->escape($id)}'";
        $this->bd->query($sql);
    }
}
