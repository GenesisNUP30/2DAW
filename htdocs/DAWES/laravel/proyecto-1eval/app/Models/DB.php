<?php

namespace App\Models;

// Incluir archivo de configuración
include_once __DIR__ . '/DB.conf.php';

use mysqli;

class DB
{
    private $link;
    private $result;
    private $regActual;
    private $base_datos;

    static $_instance;

    /* Constructor privado */
    private function __construct()
    {
        $this->Conectar($GLOBALS['db_conf']);
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
        $this->link->query("SET NAMES 'utf8'");
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

        $this->regActual = $result->fetch_assoc(); // Cambiado a fetch_assoc para que devuelva un array asociativo
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
            return $rs->fetch_assoc(); // Devuelve un array asociativo
        }
        return NULL;
    }
}