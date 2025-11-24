<?php

namespace App\Models;

use App\Models\DB;

class Tareas
{
    private $bd;

    public function __construct()
    {
        $this->bd = DB::getInstance();
    }

    public function listarTareas()
    {
        $sql = 'SELECT * FROM tareas';
        $resultado = $this->bd->query($sql);

        $tareas = [];
        while ($fila = $this->bd->LeeRegistro($resultado)) {
            $tareas[] = $fila;
        }
        return $tareas;
    }

    public function registraAlta(array $datos)
    {
        $sql = "INSERT INTO tareas (
            nif_cif,
            persona_contacto,
            telefono,
            correo,
            descripcion,
            direccion,
            poblacion,
            codigo_postal,
            provincia,
            estado,
            operario_encargado,
            fecha_realizacion,
            anotaciones_anteriores
        ) VALUES (
            '{$this->bd->escape($datos['nif_cif'])}',
            '{$this->bd->escape($datos['persona_contacto'])}',
            '{$this->bd->escape($datos['telefono'])}',
            '{$this->bd->escape($datos['correo'])}',
            '{$this->bd->escape($datos['descripcion'])}',
            '{$this->bd->escape($datos['direccion'])}',
            '{$this->bd->escape($datos['poblacion'])}',
            '{$this->bd->escape($datos['codigo_postal'])}',
            '{$this->bd->escape($datos['provincia'])}',
            '{$this->bd->escape($datos['estado'])}',
            '{$this->bd->escape($datos['operario_encargado'])}',
            '{$this->bd->escape($datos['fecha_realizacion'])}',
            '{$this->bd->escape($datos['anotaciones_anteriores'])}'
        )";

        $this->bd->query($sql);
    }

    public function obtenerTareaPorId($id)
    {
        $sql = "SELECT * FROM tareas WHERE id = $id";
        $resultado = $this->bd->query($sql);
        return $this->bd->LeeRegistro($resultado);
    }

    public function actualizarTarea($id, array $datos)
    {
        $sql = "UPDATE tareas SET
            nif_cif = '{$this->bd->escape($datos['nif_cif'])}',
            persona_contacto = '{$this->bd->escape($datos['persona_contacto'])}',
            telefono = '{$this->bd->escape($datos['telefono'])}',
            correo = '{$this->bd->escape($datos['correo'])}',
            descripcion = '{$this->bd->escape($datos['descripcion'])}',
            direccion = '{$this->bd->escape($datos['direccion'])}',
            poblacion = '{$this->bd->escape($datos['poblacion'])}',
            codigo_postal = '{$this->bd->escape($datos['codigo_postal'])}',
            provincia = '{$this->bd->escape($datos['provincia'])}',
            estado = '{$this->bd->escape($datos['estado'])}',
            operario_encargado = '{$this->bd->escape($datos['operario_encargado'])}',
            fecha_realizacion = '{$this->bd->escape($datos['fecha_realizacion'])}',
            anotaciones_anteriores = '{$this->bd->escape($datos['anotaciones_anteriores'])}',
            anotaciones_posteriores = '{$this->bd->escape($datos['anotaciones_posteriores'])}'
            WHERE id = $id";
        $this->bd->query($sql);
    }

    public function eliminarTarea($id)
    {
        $sql = "DELETE FROM tareas WHERE id = $id";
        $this->bd->query($sql);
    }
}
