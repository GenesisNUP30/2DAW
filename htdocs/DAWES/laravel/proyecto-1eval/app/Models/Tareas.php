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
        $descripcion_tarea = $datos['descripcionTarea'];
        $direccion_tarea = $datos['direccionTarea'];
        $poblacion = $datos['poblacion'];
        $codigo_postal = $datos['codigoPostal'];
        $provincia = $datos['provincia'];
        $estado_tarea = $datos['estadoTarea'];
        $operario_encargado = $datos['operarioEncargado'];
        $fecha_realizacion = $datos['fechaRealizacion'];
        $anotaciones_anteriores = $datos['anotacionesAnteriores'];

        $sql = "INSERT INTO tareas (
            nif_cif,
            persona_contacto,
            telefono,
            correo,
            descripcion_tarea,
            direccion_tarea,
            poblacion,
            codigo_postal,
            provincia,
            estado_tarea,
            operario_encargado,
            fecha_realizacion,
            anotaciones_anteriores
        ) VALUES (
            '$nif_cif',
            '$persona_contacto',
            '$telefono',
            '$correo',
            '$descripcion_tarea',
            '$direccion_tarea',
            '$poblacion',
            '$codigo_postal',
            '$provincia',
            '$estado_tarea',
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
        return $resultado;
    }

    public function actualizarTarea($id, array $datos)
    {
        $sql = "UPDATE tareas SET
            nif_cif = '$datos[nif_cif]',
            persona_contacto = '$datos[persona_contacto]',
            telefono = '$datos[telefono]',
            correo = '$datos[correo]',
            descripcion_tarea = '$datos[descripcion_tarea]',
            direccion_tarea = '$datos[direccion_tarea]',
            poblacion = '$datos[poblacion]',
            codigo_postal = '$datos[codigo_postal]',
            provincia = '$datos[provincia]',
            estado_tarea = '$datos[estado_tarea]',
            operario_encargado = '$datos[operario_encargado]',
            fecha_realizacion = '$datos[fecha_realizacion]',
            anotaciones_anteriores = '$datos[anotaciones_anteriores]'
        WHERE id = " . (int)$id;

        $this->bd->query($sql);
    }

    public function eliminarTarea($id, array $datos) 
    {
        $sql = "DELETE FROM tareas WHERE id = $id";
        $this->bd->query($sql);
    }

}
