<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include "conexion.php";

$datos = json_decode(file_get_contents("php://input"), true);

// Variables extraídas directamente del JSON
$codInicio   = $datos['codInicio'];
$codFin      = $datos['codFin'];
$fechaInicio = $datos['fechaInicio'];
$fechaFin    = $datos['fechaFin'];
$estado      = $datos['estadoFiltro'];

// Consulta base uniendo clientes y pólizas
$sql = "SELECT c.id as cliente_id, c.nombre, c.apellidos, 
               p.id as poliza_id, p.numero_poliza, p.fecha, p.importe_total, p.estado
        FROM gestor_clientes c
        INNER JOIN gestor_polizas p ON c.id = p.cliente_id
        WHERE 1=1";

// Filtro por Rango de IDs de Clientes
if (!empty($codInicio) && !empty($codFin)) {
    $sql .= " AND c.id BETWEEN $codInicio AND $codFin";
}

// Filtro por Rango de Fechas
if (!empty($fechaInicio) && !empty($fechaFin)) {
    $sql .= " AND p.fecha BETWEEN '$fechaInicio' AND '$fechaFin'";
}

// Filtro por Estado (si es distinto de "Todas")
if (!empty($estado) && $estado !== "Todas") {
    $sql .= " AND p.estado = '$estado'";
}

// Ordenamos para que el listado sea legible
$sql .= " ORDER BY c.id ASC, p.fecha DESC";

$resultado = $conexion->query($sql);

$items = [];
if ($resultado) {
    while ($fila = $resultado->fetch_assoc()) {
        $items[] = $fila;
    }
}

// Respuesta al frontend
echo json_encode([
    "status" => true, 
    "data" => $items,
    "debug_sql" => $sql // para ver la query que se genera
]);

$conexion->close();