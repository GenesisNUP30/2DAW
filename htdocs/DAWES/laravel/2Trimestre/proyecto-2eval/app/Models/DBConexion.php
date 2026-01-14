<?php
/**
 * @file database.php
 *
 * @brief Configuración de la conexión a la base de datos del sistema.
 *
 * Este archivo devuelve un array asociativo con los parámetros necesarios
 * para establecer la conexión con la base de datos utilizada por el proyecto.
 *
 * Es cargado por las clases encargadas de gestionar el acceso a datos.
 *
 * @package Config
 */

return [

    /**
     * @var string Servidor donde se aloja la base de datos.
     */
    'servidor' => 'localhost',

    /**
     * @var string Usuario con permisos sobre la base de datos.
     */
    'usuario' => 'root',

    /**
     * @var string Contraseña asociada al usuario de la base de datos.
     */
    'password' => '',

    /**
     * @var string Nombre de la base de datos utilizada por el proyecto.
     */
    'base_datos' => 'proyecto_2eval'
];
