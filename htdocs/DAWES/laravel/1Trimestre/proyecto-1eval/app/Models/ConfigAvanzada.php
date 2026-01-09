<?php

namespace App\Models;

use App\Models\DB;

/**
 * @class ConfigAvanzada
 * @brief Gestión de configuración avanzada de la aplicación.
 *
 * Esta clase permite obtener y modificar configuraciones avanzadas
 * almacenadas en la base de datos, como límites de intentos de login,
 * duración de sesiones y políticas de contraseñas.
 *
 * @package App\Models
 */

class ConfigAvanzada
{
    /**
     * Instancia única de la clase (patrón Singleton).
     *
     * @var ConfigAvanzada|null
     */
    private static $instance = null;

    private $bd;
    
    public $provincia_defecto;
    public $poblacion_defecto;
    public $items_por_pagina;
    public $tiempo_sesion;
    public $tema;

    /**
     * Constructor
     */
    public function __construct() 
    {
        $this->bd = DB::getInstance();

        $sql = "SELECT * FROM config_avanzada";
        $res = $this->bd->query($sql);
        $fila = $this->bd->LeeRegistro($res);

        if ($fila) {
            $this->provincia_defecto = $fila['provincia_defecto'];
            $this->poblacion_defecto = $fila['poblacion_defecto'];
            $this->items_por_pagina = $fila['items_por_pagina'];
            $this->tiempo_sesion = $fila['tiempo_sesion'];
            $this->tema = $fila['tema'];
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
     * Obtiene la instancia única de la clase ConfigAvanzada.
     * 
     * Si la instancia aún no existe, la crea. Si ya existe, la devuelve.
     * 
     * @return ConfigAvanzada Instancia única de ConfigAvanzada.
     */
    public static function getInstance(): ConfigAvanzada
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function guardar(): void
    {
        // Consulta SQL para actualizar la configuración
        $sql = "UPDATE config_avanzada SET 
        provincia_defecto = '{$this->bd->escape($this->provincia_defecto)}',
        poblacion_defecto = '{$this->bd->escape($this->poblacion_defecto)}',
        items_por_pagina = '{$this->bd->escape($this->items_por_pagina)}',
        tiempo_sesion = '{$this->bd->escape($this->tiempo_sesion)}',
        tema = '{$this->bd->escape($this->tema)}'
        WHERE id = 1";

        $this->bd->query($sql);
    }
}
