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

    /**
     * Array asociativo con la configuración cargada.
     * Ejemplo: ['items_por_pagina' => '10']
     * @var array
     */
    private array $config = [];

    /**
     * Constructor
     */
    public function __construct() {}

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

    /**
     * Carga todos los valores de configuración desde la base de datos.
     */
    public function cargarConfiguracion(): void
    {
        // Lógica para cargar la configuración avanzada desde la base de datos
        // Crear instancia de DB
        $db = DB::getInstance();

        // Consulta SQL para obtener los valores de la configuración
        $db->query("SELECT clave, valor FROM configuracion");

        //Leer los registros uno a uno
        while ($fila = $db->LeeRegistro()) {
            // Asignar los valores a las propiedades correspondientes
            $this->config[$fila['clave']] = $fila['valor'];
        }
    }

    /**
     * Obtiene el valor de una configuración específica.
     * @param string $clave Clave de la configuración a obtener.
     * @param mixed $default Valor por defecto si la clave no existe.
     * @return mixed Devuelve el valor de la configuración o el valor por defecto si no existe.
     */
    public function get(string $clave, $default = null)
    {
        return $this->config[$clave] ?? $default;
    }

    /**
     * Establece el valor de una configuración específica.
     * @param string $clave Clave de la configuración a establecer.
     * @param string $valor Valor a establecer.
     * @return void
     */
    public function set(string $clave, string $valor): void
    {
        // Crear instancia de DB
        $db = DB::getInstance();

        // Escapar valores para evitar inyección SQL
        $clave_sql = $db->escape($clave);
        $valor_sql = $db->escape($valor);

        // Consulta SQL para actualizar la configuración
        $db->query("UPDATE configuracion SET valor = '$valor_sql' WHERE clave = '$clave_sql'");

        // Actualizar el valor en el array local
        $this->config[$clave] = $valor;
    }
}
