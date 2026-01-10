<?php

namespace App\Models;

use App\Models\DB;

/**
 * @class ConfigAvanzada
 * @brief Gestión de la configuración avanzada de la aplicación.
 *
 * Esta clase permite obtener y modificar configuraciones avanzadas
 * almacenadas en la base de datos, como:
 * - Límites de intentos de login
 * - Duración de sesiones
 * - Políticas de contraseñas
 * - Items por página y tema de la aplicación
 *
 * Implementa el patrón Singleton para asegurar que solo exista
 * una instancia de configuración en toda la aplicación.
 *
 * @package App\Models
 * @see DB
 */
class ConfigAvanzada
{
    /**
     * Instancia única de la clase (patrón Singleton)
     *
     * @var ConfigAvanzada|null
     */
    private static $instance = null;

    /**
     * Instancia de la base de datos
     *
     * @var DB
     */
    private $bd;

    /** @var string Provincia por defecto */
    public $provincia_defecto;

    /** @var string Población por defecto */
    public $poblacion_defecto;

    /** @var int Número de ítems por página */
    public $items_por_pagina;

    /** @var int Tiempo de sesión en segundos */
    public $tiempo_sesion;

    /** @var string Tema de la aplicación ('claro' o 'oscuro') */
    public $tema;

    /**
     * @brief Constructor
     *
     * Carga la configuración actual desde la base de datos
     * y asigna los valores a las propiedades de la clase.
     *
     * @see DB
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
     * @brief Evita la clonación de la instancia (patrón Singleton).
     *
     * @throws \Exception Siempre, para evitar duplicación de la instancia
     */
    public function __clone()
    {
        throw new \Exception("No se permite la clonación de esta clase.");
    }

    /**
     * @brief Obtiene la instancia única de ConfigAvanzada.
     *
     * Si la instancia aún no existe, la crea; si ya existe, devuelve la existente.
     *
     * @return ConfigAvanzada Instancia única de ConfigAvanzada
     * @see __construct()
     */
    public static function getInstance(): ConfigAvanzada
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @brief Guarda los cambios de la configuración en la base de datos.
     *
     * Actualiza la tabla `config_avanzada` con los valores actuales
     * de las propiedades de la clase.
     *
     * @return void
     * @see DB::query()
     */
    public function guardar(): void
    {
        $sql = "UPDATE config_avanzada SET 
            provincia_defecto = '{$this->bd->escape($this->provincia_defecto)}',
            poblacion_defecto = '{$this->bd->escape($this->poblacion_defecto)}',
            items_por_pagina = '{$this->bd->escape($this->items_por_pagina)}',
            tiempo_sesion = '{$this->bd->escape($this->tiempo_sesion)}',
            tema = '{$this->bd->escape($this->tema)}'
            WHERE id = 1";

        $this->bd->query($sql);
    }

    /**
     * @brief Obtiene el tiempo de sesión en minutos.
     *
     * Convierte el valor almacenado en segundos a minutos.
     *
     * @return int Tiempo de sesión en minutos
     */
    public function getTiempoSesionMinutos(): int
    {
        return (int) ($this->tiempo_sesion / 60);
    }

    /**
     * @brief Establece el tiempo de sesión a partir de minutos.
     *
     * Convierte los minutos recibidos a segundos y los asigna
     * a la propiedad `tiempo_sesion`.
     *
     * @param int $minutos Tiempo de sesión en minutos
     * @return void
     */
    public function setMinutosSegundos(int $minutos): void 
    {
        $this->tiempo_sesion = $minutos * 60;
    }
}
