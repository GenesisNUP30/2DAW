<?php
namespace App\Models;

// Carga la configuración aquí
$config = require 'DBConexion.php';

use Illuminate\Support\Facades\Log;
use mysqli;

class DB {

    private $link;
    private $result;
    private $regActual;
    private $base_datos;

    static $_instance;

    /* Constructor privado */
    private function __construct()
    {
        $config = require 'DBConexion.php';
        $this->Conectar($config);  // <-- Aquí usas la variable $config, no $GLOBALS
    }

    /* Evitar clonación */
    private function __clone() {}

    /* El método clave para obtener la única instancia */
    public static function getInstance() : self
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function Conectar(array $conf) : void
    {
        // Validación básica
        if (!isset($conf['servidor']) ||
            !isset($conf['usuario']) ||
            !isset($conf['password']) ||
            !isset($conf['base_datos'])
        ) {
            throw new \Exception("Faltan parámetros de configuración");
        }

        $this->base_datos = $conf['base_datos'];

        // Conectar
        $this->link = new mysqli(
            $conf['servidor'],
            $conf['usuario'],
            $conf['password']
        );

        if ($this->link->connect_error) {
            throw new \Exception("Error de conexión: " . $this->link->connect_error);
        }

        $this->link->select_db($this->base_datos);
        
        // Log::debug("InciioCtrl::index\n".print_r($tareas, true));
        $this->link->query("SET NAMES 'utf8'");
    }

    public function escape($value): string
    {
        return $this->link->real_escape_string((string) $value);
    }

    public function query(string $sql)
    {
        $this->result = $this->link->query($sql);
        return $this->result;
    }

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

    public function RegistroActual()
    {
        return $this->regActual;
    }

    public function LastID()
    {
        return $this->link->insert_id;
    }

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