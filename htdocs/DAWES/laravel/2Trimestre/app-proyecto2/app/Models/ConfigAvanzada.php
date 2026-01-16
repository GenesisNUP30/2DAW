<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfigAvanzada extends Model
{
    /**
     * Tabla asociada con el modelo en la base de datos.
     *
     * @var string
     */
    protected $table = 'config_avanzada';

    public $timestamps = false;

    /**
     * Campos que se guardarán en la base de datos cuando se
     * haga un create o update.
     *
     * @var array<string, string>
     */
    protected $fillable = [
        'provincia_defecto',
        'poblacion_defecto',
        'items_por_pagina',
        'tiempo_sesion',
        'tema',
    ];

    public static function actual()
    {
        return self::first();
    }

}
