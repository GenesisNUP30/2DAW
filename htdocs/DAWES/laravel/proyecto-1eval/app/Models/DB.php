<?php
namespace App\Models;

use Illuminate\Support\Facades\Log;
use mysqli;

/**
 * Clase DB
 *
 * Implementa un singleton para la conexión a la base de datos MySQL usando mysqli.
 * Proporciona métodos para ejecutar consultas, escapar valores, leer registros
 * y obtener el último ID insertado.
 *
 * @package App\Models
 */
class DB {

    /**
     * Conexión mysqli
     * @var \mysqli
     */
    private $link;

    /**
     * Resultado de la última consulta ejecutada
     * @var \mysqli_result|null
     */
    private $result;

    /**
     * Registro actual leído
     * @var array|null
     */
    private $regActual;

    /**
     * Nombre de la base de datos
     * @var string
     */
    private $base_datos;

    /**
     * Instancia única del singleton
     * @var self|null
     */
    static $_instance;

    /**
     * Constructor privado.
     *
     * Inicializa la conexión a la base de datos usando la configuración de DBConexion.php.
     */
    private function __construct()
    {
        $config = require 'DBConexion.php';
        $this->Conectar($config);
    }

    /**
     * Evita la clonación del singleton.
     */
    private function __clone() {}

    /**
     * Devuelve la única instancia de DB.
     *
     * @return self Instancia única de la clase DB
     */
    public static function getInstance() : self
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Conecta a la base de datos usando la configuración proporcionada.
     *
     * @param array $conf Array con claves 'servidor', 'usuario', 'password', 'base_datos'
     * @throws \Exception Si faltan parámetros o hay error de conexión
     */
    private function Conectar(array $conf) : void
    {
        if (!isset($conf['servidor']) ||
            !isset($conf['usuario']) ||
            !isset($conf['password']) ||
            !isset($conf['base_datos'])
        ) {
            throw new \Exception("Faltan parámetros de configuración");
        }

        $this->base_datos = $conf['base_datos'];

        $this->link = new mysqli(
            $conf['servidor'],
            $conf['usuario'],
            $conf['password']
        );

        if ($this->link->connect_error) {
            throw new \Exception("Error de conexión: " . $this->link->connect_error);
        }

        $this->link->select_db($this->base_datos);
        $this->link->query("SET NAMES 'utf8'");
    }

    /**
     * Escapa un valor para usarlo en una consulta SQL.
     *
     * @param string $value Valor a escapar
     * @return string Valor escapado seguro
     */
    public function escape($value): string
    {
        return $this->link->real_escape_string((string) $value);
    }

    /**
     * Ejecuta una consulta SQL.
     *
     * @param string $sql Consulta SQL a ejecutar
     * @return \mysqli_result|bool Resultado de la consulta
     */
    public function query(string $sql)
    {
        $this->result = $this->link->query($sql);
        return $this->result;
    }

    /**
     * Lee un registro del resultado de la última consulta o del resultado especificado.
     *
     * @param \mysqli_result|null $result Resultado opcional
     * @return array|null Registro leído o null si no hay registros
     */
    public function LeeRegistro($result = NULL) : ?array
    {
        if (!$result) {
            if (!$this->result) {
                return NULL;
            }
            $result = $this->result;
        }

        $this->regActual = $result->fetch_assoc();
        return $this->regActual;
    }

    /**
     * Devuelve el último registro leído.
     *
     * @return array|null Registro actual
     */
    public function RegistroActual()
    {
        return $this->regActual;
    }

    /**
     * Devuelve el ID del último registro insertado.
     *
     * @return int Último ID insertado
     */
    public function LastID()
    {
        return $this->link->insert_id;
    }

    /**
     * Lee un único registro de una tabla según la condición.
     *
     * @param string $tabla Nombre de la tabla
     * @param string $condicion Condición WHERE
     * @param string $campos Campos a seleccionar, por defecto '*'
     * @return array|null Registro encontrado o null si no existe
     */
    public function LeeUnRegistro($tabla, $condicion, $campos='*') : ?array
    {
        $sql = "SELECT $campos FROM $tabla WHERE $condicion LIMIT 1";
        $rs = $this->link->query($sql);

        if ($rs) {
            return $rs->fetch_assoc();
        }
        return NULL;
    }
}
