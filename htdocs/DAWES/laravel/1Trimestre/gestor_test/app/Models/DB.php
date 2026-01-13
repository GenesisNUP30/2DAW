<?php
namespace App\Models;

use Illuminate\Support\Facades\Log;
use mysqli;

/**
 * @class DB
 * @brief Singleton para la conexión a la base de datos MySQL usando mysqli.
 *
 * Esta clase implementa un patrón singleton para asegurar que solo haya
 * una conexión activa a la base de datos durante la ejecución del proyecto.
 * Proporciona métodos para:
 * - Ejecutar consultas SQL.
 * - Escapar valores para evitar inyección SQL.
 * - Leer registros de resultados de consultas.
 * - Obtener el último ID insertado.
 *
 * @package App\Models
 */
class DB {

    /**
     * @var \mysqli Conexión activa a la base de datos.
     */
    private $link;

    /**
     * @var \mysqli_result|null Resultado de la última consulta ejecutada.
     */
    private $result;

    /**
     * @var array|null Último registro leído mediante `LeeRegistro`.
     */
    private $regActual;

    /**
     * @var string Nombre de la base de datos usada por la conexión.
     */
    private $base_datos;

    /**
     * @var self|null Instancia única del singleton DB.
     */
    static $_instance;

    /**
     * Constructor privado.
     *
     * Inicializa la conexión a la base de datos usando la configuración de `DBConexion.php`.
     * Evita instanciación externa directa para mantener el patrón singleton.
     *
     * @throws \Exception Si hay error de conexión o configuración incompleta.
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
     * Obtiene la instancia única de la clase DB.
     *
     * @return self Instancia única de DB.
     */
    public static function getInstance() : self
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Establece la conexión a la base de datos.
     *
     * @param array $conf Array asociativo con las claves:
     *                    - 'servidor': Servidor de la base de datos
     *                    - 'usuario': Usuario de la base de datos
     *                    - 'password': Contraseña del usuario
     *                    - 'base_datos': Nombre de la base de datos
     * @return void
     * @throws \Exception Si faltan parámetros o falla la conexión.
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
     * Escapa un valor para uso seguro en consultas SQL.
     *
     * @param string $value Valor a escapar.
     * @return string Valor escapado seguro.
     */
    public function escape($value): string
    {
        return $this->link->real_escape_string((string) $value);
    }

    /**
     * Ejecuta una consulta SQL.
     *
     * @param string $sql Consulta SQL a ejecutar.
     * @return \mysqli_result|bool Resultado de la consulta, o false en caso de error.
     */
    public function query(string $sql)
    {
        $this->result = $this->link->query($sql);
        return $this->result;
    }

    /**
     * Lee un registro del resultado de una consulta.
     *
     * @param \mysqli_result|null $result Resultado opcional. Si no se pasa, se usa el último ejecutado.
     * @return array|null Registro leído como array asociativo, o null si no hay registros.
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
     * @return array|null Último registro leído o null si no se ha leído ninguno.
     */
    public function RegistroActual()
    {
        return $this->regActual;
    }

    /**
     * Devuelve el ID del último registro insertado en la base de datos.
     *
     * @return int Último ID insertado.
     */
    public function LastID()
    {
        return $this->link->insert_id;
    }

    /**
     * Lee un único registro de una tabla según una condición.
     *
     * @param string $tabla Nombre de la tabla.
     * @param string $condicion Condición WHERE (sin la palabra WHERE).
     * @param string $campos Campos a seleccionar, por defecto '*'.
     * @return array|null Registro encontrado como array asociativo, o null si no existe.
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
