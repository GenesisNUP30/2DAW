<?php
/*
|--------------------------------------------------------------------------
| Register The Auto Loader de Composer
|--------------------------------------------------------------------------
*/
require 'vendor/autoload.php';

use Jenssegers\Blade\Blade;

/**
 * Clase auxiliar que implementa el patrón singleton para la
 * clase Blade.
 * No sería requisito indispensable, pero si es util
 * Este fichero incluye además las funcionalidades de autoload
 */
class TemplateBlade {
    // RUTA que se utilizará por defecto si no 
    // se indica otra para las vistas y cache
    const VIEWS = __DIR__ . '/views';
    const CHACHE = __DIR__ . '/cache';
    
    private static $views=self::VIEWS;
    private static $cache=self::CHACHE;
    
    //Singleton
    private static $_my_blade=NULL;
      
    /**
     * Devuelve la instancia de Blade que hemos creado
     * Patrón Singleton
     * @return type
     */
    public static function GetInstance() {
        if (! self::$_my_blade) {
            self::$_my_blade=new Blade(self::$views, self::$cache);
        }
        return self::$_my_blade;
    }
    
    private static function CheckNotInstanciated() {
        if (self::$_my_blade) {
            throw new Exception("No se pueden cambiar parámetros una vez se ha creado el objeto Blade");
        }
    }
    
    /**
     * Indica la ruta en la que se guardarán los archivos de cache
     * @param string $cache
     */
    public static function CacheFolder($cache) {
        self::CheckNotInstanciated();
        self::$cache=$cache;
    }
    
    /**
     * Ruta o rutas en las que se encuentran las vistas
     * 
     * @param string/array $views
     */
    public static function ViewFolder($views) {
        self::CheckNotInstanciated();
        self::$views=$views;
    }
}

