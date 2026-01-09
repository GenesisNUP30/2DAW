<?php

namespace App\Http\Controllers;

use App\Models\Sesion;
use App\Models\ConfigAvanzada;

/**
 * @class ConfigAvanzadaCtrl
 * 
 * @brief Controlador para la configuración avanzada de la aplicación.
 * 
 * Este controlador gestionará las opciones avanzadas de configuración
 * que los usuarios pueden ajustar según sus preferencias.
 * 
 * Para poder acceder a la configuración, el usuario debe estar autenticado correctamente.
 * @package App\Http\Controllers
 */
class ConfigAvanzadaCtrl
{
    public function mostrar()
    {
        $sesion = Sesion::getInstance();
        $sesion->onlyLogged(); // Asegura que el usuario esté autenticado

        $config = ConfigAvanzada::getInstance();

        return view('configavanzada', [
            'configavanzada' => $config
        ]);
    }


    public function guardar()
    {
        $sesion = Sesion::getInstance();
        $sesion->onlyLogged(); // Asegura que el usuario esté autenticado
        $config = ConfigAvanzada::getInstance();

        // Si se reciben datos de formulario, actualiza la configuración
        if ($_POST) {
            $config->provincia_defecto = $_POST['provincia_defecto'] ?? $config->provincia_defecto;
            $config->poblacion_defecto = $_POST['poblacion_defecto'] ?? $config->poblacion_defecto;
            $config->items_por_pagina = $_POST['items_por_pagina'] ?? $config->items_por_pagina;
            $config->tiempo_sesion = $_POST['tiempo_sesion'] ?? $config->tiempo_sesion;
            $config->tema = $_POST['tema'] ?? $config->tema;

            // Guardar el tema en la sesión para aplicarlo en toda la web
            $_SESSION['tema'] = $config->tema;

            $config->guardar(); // Guarda los cambios en la base de datos
            miredirect('/');
        }

        // Mostrar el formulario de configuración
        return view('configavanzada', [
            'configavanzada' => $config,
            'mensaje' => 'Configuración actualizada con éxito.'
        ]);
    }
}
