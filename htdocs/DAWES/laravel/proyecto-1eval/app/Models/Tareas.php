<?php

namespace App\Models;

use App\Models\DB;

/**
 * @class Tareas
 * @brief Modelo para la gestión de tareas en la base de datos.
 *
 * Este modelo proporciona métodos para:
 * - Listar tareas completas o paginadas
 * - Registrar nuevas tareas
 * - Obtener una tarea por su ID
 * - Actualizar tareas
 * - Completar tareas
 * - Eliminar tareas
 *
 * Utiliza la clase singleton DB para todas las operaciones de base de datos.
 *
 * @package App\Models
 */
class Tareas
{
    /**
     * Instancia de la base de datos.
     *
     * @var DB
     */
    private $bd;

    /**
     * Constructor del modelo.
     *
     * Obtiene la instancia de la base de datos mediante DB::getInstance().
     */
    public function __construct()
    {
        $this->bd = DB::getInstance();
    }

    /**
     * Lista todas las tareas de la base de datos.
     *
     * @return array Devuelve un array con todas las tareas.
     */
    public function listarTareas(): array
    {
        $sql = 'SELECT * FROM tareas';
        $resultado = $this->bd->query($sql);

        $tareas = [];
        while ($fila = $this->bd->LeeRegistro($resultado)) {
            $tareas[] = $fila;
        }
        return $tareas;
    }

    /**
     * Lista todas las tareas de la base de datos, paginadas. 
     * Permite filtrar solo las tareas pendientes si se indica.
     * 
     * @param int $pagina Número de página a mostrar.
     * @param int $porPagina Número de registros por página.
     * @param bool $soloPendientes Si es true, solo muestra tareas pendientes.
     * @return array Devuelve un array con todas las tareas y el total de registros.
     */

    public function listarTareasPaginadas(int $pagina, int $porPagina, bool $soloPendientes = false): array
    {
        $offset = ($pagina - 1) * $porPagina;

        // Condición para filtrar solo tareas pendientes en la sentencia sql
        if ($soloPendientes) {
            $where = 'WHERE estado = "P"';
        } else {
            $where = "";
        }

        // Total de registros
        $sqlTotal = "SELECT COUNT(*) AS total FROM tareas $where";
        $resTotal = $this->bd->query($sqlTotal);
        $filaTotal = $this->bd->LeeRegistro($resTotal);
        $totalRegistros = (int)$filaTotal['total'];

        // Registros de la página actual, ordenados por fecha de realización descendente
        $sql = "SELECT * FROM tareas $where
            ORDER BY fecha_realizacion DESC, id DESC
            LIMIT $offset, $porPagina";

        $resultado = $this->bd->query($sql);

        $tareas = [];
        while ($fila = $this->bd->LeeRegistro($resultado)) {
            $tareas[] = $fila;
        }

        return [
            'tareas' => $tareas,
            'total' => $totalRegistros
        ];
    }

    /**
     * Registra una nueva tarea en la base de datos.
     *
     * Escapa todos los datos para evitar inyección SQL.
     *
     * @param array $datos Array asociativo con los campos de la tarea.
     *                     Claves esperadas:
     *                     'nif_cif', 'persona_contacto', 'telefono', 'correo',
     *                     'descripcion', 'direccion', 'poblacion', 'codigo_postal',
     *                     'provincia', 'estado', 'operario_encargado',
     *                     'fecha_realizacion', 'anotaciones_anteriores'.
     * @return void
     */
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
            '{$this->bd->escape($datos['anotaciones'])}'
        )";

        $this->bd->query($sql);
    }

    /**
     * Obtiene una tarea por su ID.
     *
     * @param int $id ID de la tarea
     * @return array|null Devuelve un array con los datos de la tarea o null si no existe.
     */
    public function obtenerTareaPorId($id)
    {
        $sql = "SELECT * FROM tareas WHERE id = $id";
        $resultado = $this->bd->query($sql);
        return $this->bd->LeeRegistro($resultado);
    }

    /**
     * Actualiza los datos completos de una tarea existente.
     *
     * @param int $id ID de la tarea a actualizar
     * @param array $datos Array asociativo con los campos a actualizar.
     *                     Claves esperadas:
     *                     'nif_cif', 'persona_contacto', 'telefono', 'correo',
     *                     'descripcion', 'direccion', 'poblacion', 'codigo_postal',
     *                     'provincia', 'estado', 'operario_encargado',
     *                     'fecha_realizacion', 'anotaciones_anteriores',
     *                     'anotaciones_posteriores'.
     * @return void
     */
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

    /**
     * Completa una tarea, actualizando su estado y anotaciones posteriores.
     *
     * @param int $id ID de la tarea a completar
     * @param array $datos Array asociativo con claves:
     *                     'estado' => nuevo estado de la tarea
     *                     'anotaciones_posteriores' => anotaciones posteriores
     * @return void
     */
    public function completarTarea($id, array $datos)
    {
        $sql = "UPDATE tareas SET
            estado = '{$this->bd->escape($datos['estado'])}',
            anotaciones_posteriores = '{$this->bd->escape($datos['anotaciones_posteriores'])}',
            fecha_realizacion = " . ($datos['fecha_realizacion']
            ? "'" . $this->bd->escape($datos['fecha_realizacion']) . "'"
            : "NULL") . ",
            fichero_resumen = " . ($datos['fichero_resumen']
            ? "'" . $this->bd->escape($datos['fichero_resumen']) . "'"
            : "NULL") . "
            WHERE id = $id";
        $this->bd->query($sql);
    }

    /**
     * Elimina una tarea de la base de datos.
     *
     * @param int $id ID de la tarea a eliminar
     * @return void
     */
    public function eliminarTarea($id)
    {
        $sql = "DELETE FROM tareas WHERE id = $id";
        $this->bd->query($sql);
    }
}
