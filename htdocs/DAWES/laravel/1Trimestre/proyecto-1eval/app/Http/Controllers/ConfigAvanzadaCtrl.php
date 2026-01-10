<?php

namespace App\Http\Controllers;

use App\Models\Sesion;
use App\Models\ConfigAvanzada;
use App\Models\Funciones;

/**
 * @class ConfigAvanzadaCtrl
 * @brief Controlador para la gestión de la configuración avanzada de la aplicación.
 *
 * Este controlador permite mostrar y actualizar los ajustes avanzados que los usuarios
 * pueden modificar, como:
 * - Tiempo de sesión inactiva
 * - Número de ítems por página
 * - Tema de la aplicación
 *
 * Requiere que el usuario esté autenticado.
 *
 * @package App\Http\Controllers
 * @see ConfigAvanzada
 * @see Sesion
 * @see Funciones
 */
class ConfigAvanzadaCtrl
{
    /**
     * @brief Muestra el formulario de configuración avanzada.
     *
     * Carga la configuración actual desde la base de datos y la pasa a la vista.
     * Se asegura de que el usuario esté logueado antes de mostrar el formulario.
     *
     * @return array Devuelve un array con los datos de configuración para la vista.
     * @see ConfigAvanzada
     */
    public function mostrar()
    {
        $sesion = Sesion::getInstance();
        $sesion->onlyLogged();

        $config = ConfigAvanzada::getInstance();

        return view('configavanzada', [
            'configavanzada' => $config,
            'errores' => Funciones::$errores ?? []
        ]);
    }

    /**
     * @brief Procesa el formulario de configuración avanzada y guarda los cambios.
     *
     * Realiza los siguientes pasos:
     * 1. Verifica que el usuario esté logueado.
     * 2. Reinicia los errores de validación en Funciones::$errores.
     * 3. Valida los datos recibidos usando filtraDatos().
     * 4. Si hay errores, devuelve la vista con los errores.
     * 5. Si no hay errores, actualiza la configuración y guarda los cambios en la BD.
     * 6. Redirige al inicio tras guardar.
     *
     * @return mixed Devuelve la vista con errores o redirige al inicio.
     * @see filtraDatos()
     * @see ConfigAvanzada::setMinutosSegundos()
     * @see ConfigAvanzada::guardar()
     */
    public function guardar()
    {
        $sesion = Sesion::getInstance();
        $sesion->onlyLogged();
        $config = ConfigAvanzada::getInstance();

        if ($_POST) {
            // Reiniciar errores
            Funciones::$errores = [];

            // Validar datos
            $this->filtraDatos();

            // Si hay errores → mostrar formulario con errores
            if (!empty(Funciones::$errores)) {
                return view('configavanzada', [
                    'configavanzada' => $config,
                    'errores' => Funciones::$errores
                ]);
            }

            // Actualizar configuración
            $config->provincia_defecto = $_POST['provincia_defecto'] ?? $config->provincia_defecto;
            $config->poblacion_defecto = $_POST['poblacion_defecto'] ?? $config->poblacion_defecto;
            $config->items_por_pagina = $_POST['items_por_pagina'] ?? $config->items_por_pagina;

            // ⚡ Convertir minutos a segundos usando setter 
            if (isset($_POST['tiempo_sesion_minutos'])) {
                $config->setMinutosSegundos((int)$_POST['tiempo_sesion_minutos']);
            }

            $config->tema = $_POST['tema'] ?? $config->tema;

            // Guardar tema en la sesión
            $_SESSION['tema'] = $config->tema;

            // Guardar cambios en base de datos
            $config->guardar();

            // Redirigir al inicio
            miredirect('/');
        }

        // Mostrar formulario con mensaje de éxito
        return view('configavanzada', [
            'configavanzada' => $config,
            'mensaje' => 'Configuración actualizada con éxito.',
            'errores' => Funciones::$errores ?? []
        ]);
    }

    /**
     * @brief Valida los datos recibidos desde el formulario de configuración avanzada.
     *
     * Realiza comprobaciones sobre:
     * - Tiempo de sesión en minutos: debe ser numérico y >= 1.
     * - Número de ítems por página: debe ser >= 1.
     *
     * Los errores se almacenan en la variable estática `Funciones::$errores`.
     *
     * @return void No devuelve nada; solo actualiza Funciones::$errores.
     *
     * @see Funciones::$errores
     */
    public function filtraDatos()
    {
        extract($_POST);

        // Validar tiempo de sesión
        if (isset($tiempo_sesion_minutos)) {
            if (!is_numeric($tiempo_sesion_minutos) || $tiempo_sesion_minutos < 1) {
                Funciones::$errores['tiempo_sesion_minutos'] = "El tiempo de sesión debe ser un número mayor o igual a 1 minuto";
            }
        }

        // Validar items por página
        if (isset($items_por_pagina)) {
            if (!is_numeric($items_por_pagina) || $items_por_pagina < 1) {
                Funciones::$errores['items_por_pagina'] = "Los items por página no pueden ser menores a 1";
            }
        }
    }
}
