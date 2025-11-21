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
        $nif_cif = $datos['nifCif'];
        $persona_contacto = $datos['personaNombre'];
        $telefono = $datos['telefono'];
        $correo = $datos['correo'];
        $descripcion = $datos['descripcion'];
        $direccion = $datos['direccionTarea'];
        $poblacion = $datos['poblacion'];
        $codigo_postal = $datos['codigoPostal'];
        $provincia = $datos['provincia'];
        $estado = $datos['estado'];
        $operario_encargado = $datos['operarioEncargado'];
        $fecha_realizacion = $datos['fechaRealizacion'];
        $anotaciones_anteriores = $datos['anotacionesAnteriores'];

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
            '$nif_cif',
            '$persona_contacto',
            '$telefono',
            '$correo',
            '$descripcion',
            '$direccion',
            '$poblacion',
            '$codigo_postal',
            '$provincia',
            '$estado',
            '$operario_encargado',
            '$fecha_realizacion',
            '$anotaciones_anteriores'
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
        $nif_cif = $datos['nifCif'];
        $persona_contacto = $datos['personaNombre'];
        $telefono = $datos['telefono'];
        $correo = $datos['correo'];
        $descripcion = $datos['descripcion'];
        $direccion = $datos['direccionTarea'];
        $poblacion = $datos['poblacion'];
        $codigo_postal = $datos['codigoPostal'];
        $provincia = $datos['provincia'];
        $estado = $datos['estado'];
        $operario_encargado = $datos['operarioEncargado'];
        $fecha_realizacion = $datos['fechaRealizacion'];
        $anotaciones_anteriores = $datos['anotacionesAnteriores'];

        $sql = "UPDATE tareas SET
            nif_cif = '$nif_cif',
            persona_contacto = '$persona_contacto',
            telefono = '$telefono',
            correo = '$correo',
            descripcion = '$descripcion',
            direccion = '$direccion',
            poblacion = '$poblacion',
            codigo_postal = '$codigo_postal',
            provincia = '$provincia',
            estado = '$estado',
            operario_encargado = '$operario_encargado',
            fecha_realizacion = '$fecha_realizacion',
            anotaciones_anteriores = '$anotaciones_anteriores'
            WHERE id = " . (int)$id;

        $this->bd->query($sql);
    }

    public function eliminarTarea($id)
    {
        $sql = "DELETE FROM tareas WHERE id = $id";
        $this->bd->query($sql);
    }
}
