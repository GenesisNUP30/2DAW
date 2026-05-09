<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

/**
 * @class AppServiceProvider
 * @brief Proveedor de servicios principal de la aplicación.
 * * Este provider se encarga de inicializar y configurar servicios globales antes 
 * de que se procesen las rutas o los controladores. Es el lugar idóneo para 
 * definir configuraciones que afectan a todo el framework, como el motor de 
 * paginación o las directivas personalizadas de Blade.
 * * @package App\Providers
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * @brief Registra cualquier servicio de la aplicación.
     * * Este método se ejecuta antes que el método boot(). Se utiliza para 
     * enlazar interfaces a implementaciones en el contenedor de servicios.
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * @brief Arranca (bootstrap) cualquier servicio de la aplicación.
     * * Se ejecuta una vez que todos los demás proveedores de servicios han sido 
     * registrados, lo que permite acceder a todas las funcionalidades del framework.
     * * **Configuración actual**: 
     * Establece que el sistema de paginación de Laravel utilice los estilos y la 
     * estructura HTML de **Bootstrap 5**, asegurando que los enlaces de "Anterior" 
     * y "Siguiente" se rendericen correctamente con el framework CSS del proyecto.
     * * @return void
     */
    public function boot(): void
    {
        /**
         * @note Por defecto, Laravel utiliza Tailwind CSS para la paginación. 
         * Al llamar a useBootstrapFive(), forzamos la generación de HTML compatible 
         * con la última versión de Bootstrap.
         */
        Paginator::useBootstrapFive();
    }
}
